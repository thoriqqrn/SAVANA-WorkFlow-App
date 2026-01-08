<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\AnnouncementComment;
use App\Models\AnnouncementReaction;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with(['user', 'comments.user', 'reactions', 'pollOptions'])
            ->latest()
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'has_poll' => 'boolean',
            'poll_question' => 'required_if:has_poll,true|nullable|string|max:255',
            'poll_options' => 'required_if:has_poll,true|nullable|array|min:2|max:6',
            'poll_options.*' => 'required_if:has_poll,true|string|max:100',
            'poll_duration' => 'nullable|integer|min:1|max:168', // hours, max 7 days
        ]);

        $announcement = Announcement::create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'has_poll' => $request->boolean('has_poll'),
            'poll_question' => $validated['poll_question'] ?? null,
            'poll_ends_at' => $request->boolean('has_poll') && !empty($validated['poll_duration']) 
                ? now()->addHours((int) $validated['poll_duration']) 
                : null,
        ]);

        // Create poll options if poll exists
        if ($request->boolean('has_poll') && !empty($validated['poll_options'])) {
            foreach ($validated['poll_options'] as $optionText) {
                if (!empty(trim($optionText))) {
                    PollOption::create([
                        'announcement_id' => $announcement->id,
                        'option_text' => trim($optionText),
                    ]);
                }
            }
        }

        ActivityLog::log('created', 'Created announcement', $announcement);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil diposting!');
    }

    public function destroy(Announcement $announcement)
    {
        // Only creator can delete
        if ($announcement->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus ini.');
        }

        ActivityLog::log('deleted', 'Deleted announcement', $announcement);
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus!');
    }

    public function comment(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        AnnouncementComment::create([
            'announcement_id' => $announcement->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Komentar ditambahkan!');
    }

    public function react(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,love,haha,wow,sad,angry',
        ]);

        $existing = AnnouncementReaction::where('announcement_id', $announcement->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            if ($existing->type === $validated['type']) {
                // Remove reaction
                $existing->delete();
                return response()->json(['removed' => true]);
            } else {
                // Change reaction
                $existing->update(['type' => $validated['type']]);
                return response()->json(['changed' => true, 'type' => $validated['type']]);
            }
        }

        AnnouncementReaction::create([
            'announcement_id' => $announcement->id,
            'user_id' => auth()->id(),
            'type' => $validated['type'],
        ]);

        return response()->json(['added' => true, 'type' => $validated['type']]);
    }

    public function vote(Request $request, Announcement $announcement)
    {
        if (!$announcement->has_poll || !$announcement->isPollActive()) {
            return response()->json(['error' => 'Poll tidak aktif'], 400);
        }

        $validated = $request->validate([
            'option_id' => 'required|exists:poll_options,id',
        ]);

        // Check if already voted
        $existingVote = PollVote::whereIn('poll_option_id', $announcement->pollOptions->pluck('id'))
            ->where('user_id', auth()->id())
            ->first();

        if ($existingVote) {
            return response()->json(['error' => 'Anda sudah memilih'], 400);
        }

        $option = PollOption::findOrFail($validated['option_id']);

        PollVote::create([
            'poll_option_id' => $option->id,
            'user_id' => auth()->id(),
        ]);

        $option->increment('votes_count');

        // Return updated poll data
        $announcement->refresh();
        $pollData = $announcement->pollOptions->map(fn($o) => [
            'id' => $o->id,
            'text' => $o->option_text,
            'votes' => $o->votes_count,
            'percentage' => $o->percentage,
        ]);

        return response()->json([
            'success' => true,
            'total_votes' => $announcement->total_votes,
            'options' => $pollData,
        ]);
    }
}
