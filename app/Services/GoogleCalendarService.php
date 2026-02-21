<?php

namespace App\Services;

use App\Models\Timeline;
use Illuminate\Support\Facades\Log;
use Throwable;

class GoogleCalendarService
{
    private ?string $lastError = null;

    public function enabled(): bool
    {
        return (bool) config('services.google_calendar.enabled', false);
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function upsertTimelineEvent(Timeline $timeline): ?string
    {
        $this->clearLastError();

        if (!$this->enabled()) {
            return null;
        }

        try {
            $calendarService = $this->calendarService();
            $calendarId = (string) config('services.google_calendar.calendar_id');
            $eventData = $this->buildTimelineEventData($timeline);

            if ($timeline->google_event_id) {
                try {
                    $calendarService->events->get($calendarId, $timeline->google_event_id);
                    $updated = $calendarService->events->update($calendarId, $timeline->google_event_id, $eventData);

                    return $updated->getId();
                } catch (Throwable $exception) {
                    Log::warning('Google Calendar update failed, will retry as new event.', [
                        'timeline_id' => $timeline->id,
                        'google_event_id' => $timeline->google_event_id,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }

            $created = $calendarService->events->insert($calendarId, $eventData);

            return $created->getId();
        } catch (Throwable $exception) {
            $this->setLastError($exception->getMessage());
            Log::error('Failed syncing timeline to Google Calendar.', [
                'timeline_id' => $timeline->id,
                'error' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    public function deleteTimelineEvent(?string $googleEventId, ?int $timelineId = null): bool
    {
        $this->clearLastError();

        if (!$this->enabled() || !$googleEventId) {
            return false;
        }

        try {
            $calendarService = $this->calendarService();
            $calendarId = (string) config('services.google_calendar.calendar_id');
            $calendarService->events->delete($calendarId, $googleEventId);

            return true;
        } catch (Throwable $exception) {
            $this->setLastError($exception->getMessage());
            Log::error('Failed deleting Google Calendar event.', [
                'timeline_id' => $timelineId,
                'google_event_id' => $googleEventId,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function clearLastError(): void
    {
        $this->lastError = null;
    }

    private function setLastError(string $message): void
    {
        $clean = trim(str_replace(["\r", "\n"], ' ', $message));
        $this->lastError = strlen($clean) > 250 ? substr($clean, 0, 247) . '...' : $clean;
    }

    private function calendarService()
    {
        if (!class_exists(\Google\Client::class) || !class_exists(\Google\Service\Calendar::class) || !class_exists(\Google\Service\Calendar\Event::class)) {
            throw new \RuntimeException('Google API client not installed. Run: composer require google/apiclient:^2.16');
        }

        $credentialsPath = (string) config('services.google_calendar.service_account_json');
        if (!is_file($credentialsPath)) {
            throw new \RuntimeException("Google service account JSON file not found at: {$credentialsPath}");
        }

        $client = new \Google\Client();
        $client->setApplicationName((string) config('services.google_calendar.application_name', 'SAVANA'));
        $client->setAuthConfig($credentialsPath);
        $client->setScopes([\Google\Service\Calendar::CALENDAR]);

        return new \Google\Service\Calendar($client);
    }

    private function buildTimelineEventData(Timeline $timeline)
    {
        $descriptionLines = [];

        if ($timeline->description) {
            $descriptionLines[] = $timeline->description;
            $descriptionLines[] = '';
        }

        $descriptionLines[] = 'Sumber: SAVANA Timeline';
        $descriptionLines[] = 'Tipe: ' . ucfirst($timeline->type);

        if ($timeline->department?->name) {
            $descriptionLines[] = 'Departemen: ' . $timeline->department->name;
        }

        if ($timeline->program?->name) {
            $descriptionLines[] = 'Program: ' . $timeline->program->name;
        }

        $payload = [
            'summary' => $timeline->title,
            'description' => implode("\n", $descriptionLines),
            'start' => [
                'date' => $timeline->start_date->format('Y-m-d'),
            ],
            'end' => [
                'date' => $timeline->end_date->copy()->addDay()->format('Y-m-d'),
            ],
        ];

        return new \Google\Service\Calendar\Event($payload);
    }
}
