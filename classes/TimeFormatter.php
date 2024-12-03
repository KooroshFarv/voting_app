<?php
class TimeFormatter {
    public static function formatTimestamp($timestamp) {
        $timeDifference = time() - $timestamp;

        if ($timeDifference < 60) {
            return $timeDifference . " seconds ago";
        } elseif ($timeDifference < 3600) {
            $minutes = floor($timeDifference / 60);
            return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
        } elseif ($timeDifference < 86400) {
            $hours = floor($timeDifference / 3600);
            return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
        } elseif ($timeDifference < 2592000) {
            $days = floor($timeDifference / 86400);
            return $days . " day" . ($days > 1 ? "s" : "") . " ago";
        } elseif ($timeDifference < 31536000) {
            $months = floor($timeDifference / 2592000);
            return $months . " month" . ($months > 1 ? "s" : "") . " ago";
        } else {
            return date("M d, Y", $timestamp); 
        }
    }
}
?>
