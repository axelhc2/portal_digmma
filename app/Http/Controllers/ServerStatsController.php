<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServerStatsController extends Controller
{
    public function getStats()
    {
        // CPU Usage
        $cpuUsage = $this->getCpuUsage();
        
        // RAM Usage
        $ramUsage = $this->getRamUsage();
        
        // Disk Usage
        $diskUsage = $this->getDiskUsage();
        
        // Ping to 1.1.1.1
        $ping = $this->getPing();

        return response()->json([
            'cpu' => $cpuUsage,
            'ram' => $ramUsage,
            'disk' => $diskUsage,
            'ping' => $ping
        ]);
    }

    private function getCpuUsage()
    {
        // Lecture des statistiques CPU
        $stat1 = file('/proc/stat');
        usleep(100000); // Attendre 100ms
        $stat2 = file('/proc/stat');

        $cpu1 = explode(' ', preg_replace('/\s+/', ' ', $stat1[0]));
        $cpu2 = explode(' ', preg_replace('/\s+/', ' ', $stat2[0]));

        // Calcul de l'utilisation CPU
        $diff = [];
        for ($i = 1; $i < 8; $i++) {
            $diff[$i] = $cpu2[$i] - $cpu1[$i];
        }

        $total = array_sum($diff);
        $idle = $diff[4];
        
        return round(100 * ($total - $idle) / $total);
    }

    private function getRamUsage()
    {
        $meminfo = file('/proc/meminfo');
        $meminfo = array_filter($meminfo);
        
        $total = 0;
        $free = 0;
        $cached = 0;
        $buffers = 0;
        
        foreach ($meminfo as $line) {
            if (strpos($line, 'MemTotal:') === 0) {
                $total = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT);
            }
            if (strpos($line, 'MemFree:') === 0) {
                $free = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT);
            }
            if (strpos($line, 'Cached:') === 0) {
                $cached = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT);
            }
            if (strpos($line, 'Buffers:') === 0) {
                $buffers = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT);
            }
        }
        
        $used = $total - $free - $cached - $buffers;
        return round(($used / $total) * 100);
    }

    private function getDiskUsage()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        // Convertir en GB pour plus de précision
        $total_gb = $total / (1024 * 1024 * 1024);
        $used_gb = $used / (1024 * 1024 * 1024);
        
        return round(($used_gb / $total_gb) * 100);
    }

    private function getPing()
    {
        $ip = '1.1.1.1';
        $ping = shell_exec("ping -c 1 -W 1 $ip");
        
        if (preg_match('/time=([0-9.]+) ms/', $ping, $matches)) {
            return round($matches[1]);
        }
        
        return 0;
    }
}
