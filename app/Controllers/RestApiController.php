<?php

namespace WordCamp\App\Controllers;


use DateTime;
use DateTimeZone;
use WP_REST_Request;
use wpdb;

class RestApiController
{
    public function events(WP_REST_Request $request)
    {
        global $wpdb;

        $tableName = $wpdb->prefix.'wep_events';

        $events = $wpdb->get_results("SELECT * FROM $tableName");

        wp_send_json(!empty($events) ? $events : [], 200);
    }

    public function attendee(WP_REST_Request $request)
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $id = $request->get_param('id');

        $tableName = $wpdb->prefix.'wep_attendees';
        $attendee = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $tableName WHERE attendee_uid = %d",
                $id
            )
        );

        if (!$attendee) {
            wp_send_json([
                "message" => "Not found",
                "events" => []
            ], 200) ;
        }

        $attendee->events = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT e.id, e.title, ae.created_at FROM {$wpdb->prefix}wep_attendee_events as ae
                    INNER JOIN {$wpdb->prefix}wep_events as e ON ae.event_id = e.id 
                    WHERE ae.attendee_id = %d",
                $attendee->id
            )
        );

        wp_send_json($attendee, 200);
    }

    public function attendeeEvent(WP_REST_Request $request)
    {
        $attendee_id = $request->get_param('attendee_id');
        $event_id = $request->get_param('event_id');

        /** @var wpdb $wpdb */
        global $wpdb;

        $attendee = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wep_attendees WHERE id = %d",
                $attendee_id
            )
        );

        if (!$attendee) {
            wp_send_json([
                "message" => "Attendee not found"
            ], 401);
        }

        $event = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wep_events WHERE id = %d",
                $event_id
            )
        );

        if (!$event) {
            wp_send_json([
                "message" => "Event not found"
            ], 401);
        }

        $timezone = new DateTimeZone('Asia/Dhaka');
        $date = new DateTime('now', $timezone);
        $current_timestamp = $date->format('Y-m-d H:i:s');

        $wpdb->get_results(
            $wpdb->prepare(
                "INSERT INTO {$wpdb->prefix}wep_attendee_events
                    (attendee_id, event_id, created_at)
                    VALUES (%s, %d, %s)",
                $attendee_id,
                $event_id,
                $current_timestamp
            )
        );

        $attendee->events = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT e.id, e.title, ae.created_at FROM {$wpdb->prefix}wep_attendee_events as ae
                    INNER JOIN {$wpdb->prefix}wep_events as e ON ae.event_id = e.id 
                    WHERE ae.attendee_id = %d",
                $attendee->id
            )
        );

        wp_send_json($attendee, 200);
    }

}