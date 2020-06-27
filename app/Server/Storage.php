<?php

namespace App\Server;

class Storage {

    public function getAvailableSpace(): array {
        exec('df -h', $output);
        $drivesInfoData = preg_grep('/\/dev\/sd/', $output);
        $driveNames = $this->_getDriveNames();

        $drivesInfo = [];
        foreach ($drivesInfoData as $driveInfoData) {
            $infoParts = array_map('trim', explode(' ', $driveInfoData));
            $driveName = $driveNames[$infoParts[0]];
            $totalSpace = $infoParts[1];
            $availableSpace = $infoParts[3];
            $usagePercent = str_replace('%', '', $infoParts[4]);

            $drivesInfo[] = [
                'name' => $driveName,
                'total' => $totalSpace,
                'available' => $availableSpace,
                'usage_percent' => $usagePercent,
            ];
        }

        return $drivesInfo;
    }

    private function _getDriveNames(): array {
        return [
            '/dev/sda1' => 'Аниме и сериалы',
            '/dev/sdb1' => 'Фильмы',
        ];
    }
}