<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Brand;
use App\Models\StorageDevice;
use App\Models\SocketType;
use App\Models\RAMMemoryType;
use App\Models\RAMMemory;
use App\Models\Processor;
use App\Models\PowerSupply;
use App\Models\Motherboard;
use App\Models\MachineHasStorageDevice;
use App\Models\Machine;
use App\Models\GraphicCard;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $table = [
            [
              "id" => 1,
              "username" => "joaomartinscoube",
              "password" => "senai_701",
              "accessToken" => ""
            ],
            [
              "id" => 2,
              "username" => "robertosimonsen",
              "password" => "senai_101",
              "accessToken" => ""
            ],
            [
              "id" => 3,
              "username" => "franciscomatarazzo",
              "password" => "senai_107",
              "accessToken" => ""
            ]
        ];
        foreach ($table as $value) {
            User::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "Intel"
            ],
            [
              "id" => 2,
              "name" => "AMD"
            ],
            [
              "id" => 3,
              "name" => "ASUS"
            ],
            [
              "id" => 4,
              "name" => "Nvidia"
            ],
            [
              "id" => 5,
              "name" => "Corsair"
            ],
            [
              "id" => 6,
              "name" => "Kingston"
            ],
            [
              "id" => 7,
              "name" => "HyperX"
            ],
            [
              "id" => 8,
              "name" => "Gigabyte"
            ],
            [
              "id" => 9,
              "name" => "ASRock"
            ],
            [
              "id" => 10,
              "name" => "MSi"
            ],
            [
              "id" => 11,
              "name" => "XPG"
            ],
            [
              "id" => 12,
              "name" => "Samsung"
            ],
            [
              "id" => 13,
              "name" => "Western Digital"
            ],
            [
              "id" => 14,
              "name" => "Seagate"
            ],
            [
              "id" => 15,
              "name" => "EVGA"
            ],
            [
              "id" => 16,
              "name" => "Galax"
            ],
            [
              "id" => 17,
              "name" => "XFX"
            ],
            [
              "id" => 18,
              "name" => "Sapphire"
            ],
            [
              "id" => 19,
              "name" => "PowerColor"
            ]
        ];
        foreach ($table as $value) {
            Brand::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "XPG Gammix S50",
              "imageUrl" => "18",
              "brandId" => 11,
              "storageDeviceType" => "ssd",
              "size" => 2048,
              "storageDeviceInterface" => "m2"
            ],
            [
              "id" => 2,
              "name" => "Corsair Force Series MP600",
              "imageUrl" => "19",
              "brandId" => 5,
              "storageDeviceType" => "ssd",
              "size" => 2048,
              "storageDeviceInterface" => "m2"
            ],
            [
              "id" => 3,
              "name" => "Samsung 970 EVO Plus",
              "imageUrl" => "20",
              "brandId" => 12,
              "storageDeviceType" => "ssd",
              "size" => 1024,
              "storageDeviceInterface" => "m2"
            ],
            [
              "id" => 4,
              "name" => "WD Purple Surveillance 3.5'",
              "imageUrl" => "21",
              "brandId" => 13,
              "storageDeviceType" => "hdd",
              "size" => 12288,
              "storageDeviceInterface" => "sata"
            ],
            [
              "id" => 5,
              "name" => "Seagate BarraCuda Pro",
              "imageUrl" => "22",
              "brandId" => 14,
              "storageDeviceType" => "hdd",
              "size" => 10240,
              "storageDeviceInterface" => "sata"
            ]
        ];
        foreach ($table as $value) {
            StorageDevice::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "AM4"
            ],
            [
              "id" => 2,
              "name" => "LGA 1151"
            ],
            [
              "id" => 3,
              "name" => "LGA 2066"
            ],
            [
              "id" => 4,
              "name" => "TR4"
            ],
            [
              "id" => 5,
              "name" => "sTRX4"
            ]
        ];
        foreach ($table as $value) {
            SocketType::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "DDR3"
            ],
            [
              "id" => 2,
              "name" => "DDR4"
            ]
        ];
        foreach ($table as $value) {
            RAMMemoryType::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "HyperX Fury 32GB 3000MHz",
              "imageUrl" => "13",
              "brandId" => 7,
              "size" => 32768,
              "ramMemoryTypeId" => 2,
              "frequency" => 3000
            ],
            [
              "id" => 2,
              "name" => "HyperX Fury 32GB 2666MHz",
              "imageUrl" => "14",
              "brandId" => 7,
              "size" => 32768,
              "ramMemoryTypeId" => 2,
              "frequency" => 2666
            ],
            [
              "id" => 3,
              "name" => "HyperX Fury 32GB 2400MHz",
              "imageUrl" => "15",
              "brandId" => 7,
              "size" => 32768,
              "ramMemoryTypeId" => 2,
              "frequency" => 2400
            ],
            [
              "id" => 4,
              "name" => "Corsair Vengeance 8GB 1600Mhz",
              "imageUrl" => "16",
              "brandId" => 5,
              "size" => 8192,
              "ramMemoryTypeId" => 1,
              "frequency" => 1600
            ],
            [
              "id" => 5,
              "name" => "HyperX Fury 8GB 1600MHz",
              "imageUrl" => "17",
              "brandId" => 7,
              "size" => 8192,
              "ramMemoryTypeId" => 1,
              "frequency" => 1600
            ]
        ];
        foreach ($table as $value) {
            RAMMemory::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "i9-9980XE Skylake",
              "imageUrl" => "6",
              "brandId" => 1,
              "socketTypeId" => 3,
              "cores" => 18,
              "baseFrequency" => 3000,
              "maxFrequency" => 4400,
              "cacheMemory" => 25344,
              "tdp" => 165
            ],
            [
              "id" => 2,
              "name" => "Ryzen Threadripper 2990WX",
              "imageUrl" => "7",
              "brandId" => 2,
              "socketTypeId" => 5,
              "cores" => 32,
              "baseFrequency" => 3000,
              "maxFrequency" => 4200,
              "cacheMemory" => 65536,
              "tdp" => 250
            ],
            [
              "id" => 3,
              "name" => "Ryzen Threadripper 3960X",
              "imageUrl" => "9",
              "brandId" => 2,
              "socketTypeId" => 5,
              "cores" => 24,
              "baseFrequency" => 3800,
              "maxFrequency" => 4500,
              "cacheMemory" => 131072,
              "tdp" => 280
            ],
            [
              "id" => 4,
              "name" => "i9-7920X Skylake",
              "imageUrl" => "11",
              "brandId" => 1,
              "socketTypeId" => 3,
              "cores" => 12,
              "baseFrequency" => 2900,
              "maxFrequency" => 4200,
              "cacheMemory" => 16896,
              "tdp" => 140
            ],
            [
              "id" => 5,
              "name" => "i9-10920X Cascade Lake",
              "imageUrl" => "12",
              "brandId" => 1,
              "socketTypeId" => 3,
              "cores" => 12,
              "baseFrequency" => 3500,
              "maxFrequency" => 4600,
              "cacheMemory" => 19712,
              "tdp" => 165
            ],
            [
              "id" => 6,
              "name" => " i9-9900KS Coffee Lake Refresh",
              "imageUrl" => "42",
              "brandId" => 1,
              "socketTypeId" => 2,
              "cores" => 8,
              "baseFrequency" => 4000,
              "maxFrequency" => 5000,
              "cacheMemory" => 16384,
              "tdp" => 127
            ]
        ];
        foreach ($table as $value) {
            Processor::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "AX1200i",
              "imageUrl" => "28",
              "brandId" => 5,
              "potency" => 1200,
              "badge80Plus" => "platinum"
            ],
            [
              "id" => 2,
              "name" => "AX1000",
              "imageUrl" => "29",
              "brandId" => 5,
              "potency" => 1000,
              "badge80Plus" => "titanium"
            ],
            [
              "id" => 3,
              "name" => "HX750i",
              "imageUrl" => "30",
              "brandId" => 5,
              "potency" => 750,
              "badge80Plus" => "platinum"
            ],
            [
              "id" => 4,
              "name" => "RMx",
              "imageUrl" => "31",
              "brandId" => 5,
              "potency" => 750,
              "badge80Plus" => "gold"
            ],
            [
              "id" => 5,
              "name" => "SF Series 450W",
              "imageUrl" => "32",
              "brandId" => 5,
              "potency" => 450,
              "badge80Plus" => "platinum"
            ]
        ];
        foreach ($table as $value) {
            PowerSupply::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "X299X Aorus Xtreme Waterforce",
              "imageUrl" => "1",
              "brandId" => 8,
              "socketTypeId" => 3,
              "ramMemoryTypeId" => 2,
              "ramMemorySlots" => 8,
              "maxTdp" => 165,
              "sataSlots" => 8,
              "m2Slots" => 2,
              "pciSlots" => 3
            ],
            [
              "id" => 2,
              "name" => "X570 AQUA",
              "imageUrl" => "2",
              "brandId" => 9,
              "socketTypeId" => 1,
              "ramMemoryTypeId" => 2,
              "ramMemorySlots" => 4,
              "maxTdp" => 105,
              "sataSlots" => 8,
              "m2Slots" => 2,
              "pciSlots" => 3
            ],
            [
              "id" => 3,
              "name" => "MEG X570 Godlike",
              "imageUrl" => "3",
              "brandId" => 10,
              "socketTypeId" => 5,
              "ramMemoryTypeId" => 2,
              "ramMemorySlots" => 4,
              "maxTdp" => 100,
              "sataSlots" => 6,
              "m2Slots" => 3,
              "pciSlots" => 4
            ],
            [
              "id" => 4,
              "name" => "X570 Aorus Xtreme",
              "imageUrl" => "4",
              "brandId" => 8,
              "socketTypeId" => 5,
              "ramMemoryTypeId" => 2,
              "ramMemorySlots" => 4,
              "maxTdp" => 100,
              "sataSlots" => 6,
              "m2Slots" => 3,
              "pciSlots" => 3
            ],
            [
              "id" => 5,
              "name" => "Z390 Aorus Xtreme",
              "imageUrl" => "5",
              "brandId" => 8,
              "socketTypeId" => 2,
              "ramMemoryTypeId" => 2,
              "ramMemorySlots" => 4,
              "maxTdp" => 100,
              "sataSlots" => 6,
              "m2Slots" => 3,
              "pciSlots" => 3
            ],
            [
              "id" => 6,
              "name" => "X399 Aorus Xtreme",
              "imageUrl" => "8",
              "brandId" => 8,
              "socketTypeId" => 4,
              "ramMemoryTypeId" => 2,
              "ramMemorySlots" => 8,
              "maxTdp" => 250,
              "sataSlots" => 6,
              "m2Slots" => 3,
              "pciSlots" => 4
            ],
            [
              "id" => 7,
              "name" => "ROG Strix TRX40-E Gaming",
              "imageUrl" => "10",
              "brandId" => 3,
              "socketTypeId" => 5,
              "ramMemoryTypeId" => 2,
              "ramMemorySlots" => 8,
              "maxTdp" => 280,
              "sataSlots" => 8,
              "m2Slots" => 3,
              "pciSlots" => 3
            ],
            [
              "id" => 8,
              "name" => "GA-H170-GAMING 3",
              "imageUrl" => "38",
              "brandId" => 8,
              "socketTypeId" => 2,
              "ramMemoryTypeId" => 1,
              "ramMemorySlots" => 4,
              "maxTdp" => 120,
              "sataSlots" => 8,
              "m2Slots" => 2,
              "pciSlots" => 2
            ],
            [
              "id" => 9,
              "name" => "GA-H170M-D3H",
              "imageUrl" => "39",
              "brandId" => 8,
              "socketTypeId" => 2,
              "ramMemoryTypeId" => 1,
              "ramMemorySlots" => 4,
              "maxTdp" => 105,
              "sataSlots" => 8,
              "m2Slots" => 1,
              "pciSlots" => 2
            ]
        ];
        foreach ($table as $value) {
            Motherboard::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "GeForce RTX 2070 Super XC Ultra + Overclocked",
              "imageUrl" => "23",
              "brandId" => 15,
              "memorySize" => 8192,
              "memoryType" => "gddr6",
              "minimumPowerSupply" => 650,
              "supportMultiGpu" => 0
            ],
            [
              "id" => 2,
              "name" => "GeForce RTX 2080 Super HOF 10th Anniversary Edition Black Teclab",
              "imageUrl" => "24",
              "brandId" => 16,
              "memorySize" => 8192,
              "memoryType" => "gddr6",
              "minimumPowerSupply" => 650,
              "supportMultiGpu" => 1
            ],
            [
              "id" => 3,
              "name" => "GeForce RTX 2080 Ti KINGPIN Gaming",
              "imageUrl" => "25",
              "brandId" => 15,
              "memorySize" => 11264,
              "memoryType" => "gddr6",
              "minimumPowerSupply" => 650,
              "supportMultiGpu" => 1
            ],
            [
              "id" => 4,
              "name" => "Radeon Red Devil RX5700",
              "imageUrl" => "26",
              "brandId" => 19,
              "memorySize" => 8192,
              "memoryType" => "gddr6",
              "minimumPowerSupply" => 650,
              "supportMultiGpu" => 0
            ],
            [
              "id" => 5,
              "name" => "Radeon RX 5700 XT Nitro+",
              "imageUrl" => "27",
              "brandId" => 18,
              "memorySize" => 8192,
              "memoryType" => "gddr6",
              "minimumPowerSupply" => 600,
              "supportMultiGpu" => 1
            ],
            [
              "id" => 6,
              "name" => "GeForce GTX 1070 Gaming ACX 3.0",
              "imageUrl" => "41",
              "brandId" => 15,
              "memorySize" => 8192,
              "memoryType" => "gddr5",
              "minimumPowerSupply" => 450,
              "supportMultiGpu" => 1
            ]
        ];
        foreach ($table as $value) {
            GraphicCard::create($value);
        }
        $table = [
            [
              "id" => 1,
              "name" => "Infinity",
              "description" => "The highest and best you could get from a gamer machine.",
              "imageUrl" => "33",
              "motherboardId" => 1,
              "processorId" => 1,
              "ramMemoryId" => 1,
              "ramMemoryAmount" => 4,
              "graphicCardId" => 5,
              "graphicCardAmount" => 2,
              "powerSupplyId" => 1
            ],
            [
              "id" => 2,
              "name" => "Shine",
              "description" => "Light gives a huge power to someone.",
              "imageUrl" => "35",
              "motherboardId" => 7,
              "processorId" => 2,
              "ramMemoryId" => 2,
              "ramMemoryAmount" => 2,
              "graphicCardId" => 1,
              "graphicCardAmount" => 1,
              "powerSupplyId" => 3
            ],
            [
              "id" => 3,
              "name" => "Wave",
              "description" => "The sequences and perfection of waves bring this machine all the power electrons carry.",
              "imageUrl" => "37",
              "motherboardId" => 3,
              "processorId" => 3,
              "ramMemoryId" => 1,
              "ramMemoryAmount" => 2,
              "graphicCardId" => 3,
              "graphicCardAmount" => 1,
              "powerSupplyId" => 2
            ],
            [
              "id" => 4,
              "name" => "Cerberus",
              "description" => "The unexpected will bring you a lot more than you expected.",
              "imageUrl" => "34",
              "motherboardId" => 4,
              "processorId" => 2,
              "ramMemoryId" => 3,
              "ramMemoryAmount" => 2,
              "graphicCardId" => 4,
              "graphicCardAmount" => 1,
              "powerSupplyId" => 4
            ],
            [
              "id" => 5,
              "name" => "Iceberg",
              "description" => "An ice-solid experience for your gaming days.",
              "imageUrl" => "36",
              "motherboardId" => 7,
              "processorId" => 2,
              "ramMemoryId" => 1,
              "ramMemoryAmount" => 4,
              "graphicCardId" => 6,
              "graphicCardAmount" => 2,
              "powerSupplyId" => 2
            ],
            [
              "id" => 6,
              "name" => "Soft",
              "description" => "The softer version that knows how to play hard.",
              "imageUrl" => "40",
              "motherboardId" => 9,
              "processorId" => 6,
              "ramMemoryId" => 5,
              "ramMemoryAmount" => 4,
              "graphicCardId" => 6,
              "graphicCardAmount" => 1,
              "powerSupplyId" => 5
            ]
        ];
        foreach ($table as $value) {
            Machine::create($value);
        }
        $table = [
            [
              "machineId" => 1,
              "storageDeviceId" => 1,
              "amount" => 1
            ],
            [
              "machineId" => 1,
              "storageDeviceId" => 5,
              "amount" => 1
            ],
            [
              "machineId" => 2,
              "storageDeviceId" => 2,
              "amount" => 1
            ],
            [
              "machineId" => 3,
              "storageDeviceId" => 3,
              "amount" => 1
            ],
            [
              "machineId" => 3,
              "storageDeviceId" => 4,
              "amount" => 1
            ],
            [
              "machineId" => 4,
              "storageDeviceId" => 2,
              "amount" => 1
            ],
            [
              "machineId" => 5,
              "storageDeviceId" => 2,
              "amount" => 1
            ],
            [
              "machineId" => 6,
              "storageDeviceId" => 3,
              "amount" => 1
            ]
        ];
        foreach ($table as $value) {
            MachineHasStorageDevice::create($value);
        }
    }
}
