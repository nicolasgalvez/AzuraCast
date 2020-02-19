<?php
namespace App\Entity\Api;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(type="object", schema="Api_StationSchedule")
 */
class StationSchedule
{
    public const TYPE_PLAYLIST = 'playlist';
    public const TYPE_STREAMER = 'streamer';

    /**
     * Unique identifier for this schedule entry.
     * @OA\Property(example=1)
     * @var int
     */
    public $id;

    /**
     * The type of this schedule entry.
     * @OA\Property(enum={App\Entity\Api\StationSchedule::TYPE_PLAYLIST, App\Entity\Api\StationSchedule::TYPE_STREAMER}, example=App\Entity\Api\StationSchedule::TYPE_PLAYLIST)
     * @var string
     */
    public $type;

    /**
     * Either the playlist or streamer's display name.
     * @OA\Property(example="Example Schedule Entry")
     * @var string
     */
    public $name;

    /**
     * The start time of the schedule entry, in UNIX format.
     * @OA\Property(example=SAMPLE_TIMESTAMP)
     * @var int
     */
    public $start_timestamp;

    /**
     * The start time of the schedule entry, in ISO 8601 format.
     * @OA\Property(example="020-02-19T03:00:00-06:00")
     * @var string
     */
    public $start;

    /**
     * The end time of the schedule entry, in UNIX format.
     * @OA\Property(example=SAMPLE_TIMESTAMP)
     * @var int
     */
    public $end_timestamp;

    /**
     * The start time of the schedule entry, in ISO 8601 format.
     * @OA\Property(example="020-02-19T05:00:00-06:00")
     * @var string
     */
    public $end;

    /**
     * Whether the event is currently ongoing.
     * @OA\Property(example=true)
     * @var bool
     */
    public $is_now;
}
