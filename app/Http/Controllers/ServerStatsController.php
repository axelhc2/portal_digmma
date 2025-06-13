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
        // Utilisation de top pour obtenir l'utilisation CPU
        $load = shell_exec("top -bn1 | grep 'Cpu(s)' | awk '{print $2}'");
        return round((float)$load);
    }

    private function getRamUsage()
    {
        // Utilisation de free pour obtenir l'utilisation RAM
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        
        $memory_usage = $mem[2]/$mem[1]*100;
        return round($memory_usage);
    }

    private function getDiskUsage()
    {
        // Utilisation de df pour obtenir l'utilisation disque
        $df = shell_exec('df -h /');
        $df = explode("\n", $df);
        $df = explode(" ", preg_replace('/\s+/', ' ', $df[1]));
        $df = array_filter($df);
        $df = array_merge($df);
        
        // Convertir le pourcentage en nombre
        $usage = str_replace('%', '', $df[4]);
        return (int)$usage;
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
