<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

class MachineController extends Controller
{
    public function get(Request $request, $type)
    {
        $size = $request->input('pageSize') || 10;
        $page = $request->input('page') || 1;
        switch ($type) {
            case "motherboards":
                $items = Motherboard::get();
                paginate($items, $size, $page, $type);
                break;
            case "processors":
                $items = Processor::get();
                paginate($items, $size, $page, $type);
                break;
            case "ram-memories":
                $items = RAMMemory::get();
                paginate($items, $size, $page, $type);
                break;
            case "storage-devices":
                $items = StorageDevice::get();
                paginate($items, $size, $page, $type);
                break;
            case "graphic-cards":
                $items = GraphicCard::get();
                paginate($items, $size, $page, $type);
                break;
            case "power-supplies":
                $items = PowerSupply::get();
                paginate($items, $size, $page, $type);
                break;
            case "machines":
                $items = Machine::get();
                paginate($items, $size, $page, $type);
                break;
            case "brands":
                $items = Brand::get();
                $items = array_chunk($items, $size)[$page];
                foreach ($items as $value) {
                    $value->toArray();
                }
                break;
            default:
                return response(["message" => "Not Found"], 404)->header('Content-Type', 'application/json');
        }
        return response($items, 200)->header('Content-Type', 'application/json');
    }
    
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required',
            'category' => 'required',
        ], $messages = validatorMessages());
        if ($validator->fails()) {
            return convertValidator($validator);
        }
        $size = $request->input('pageSize') || 10;
        $page = $request->input('page') || 1;
        $type = $request->input('category');
        switch ($type) {
            case "motherboards":
                $items = Motherboard::where('name', 'ILIKE', '%'.trim($request->input('q')).'%')->get();
                $items = paginate($items, $size, $page, $type);
                break;
            case "processors":
                $items = Processor::where('name', 'ILIKE', '%'.trim($request->input('q')).'%')->get();
                $items = paginate($items, $size, $page, $type);
                break;
            case "ram-memories":
                $items = RAMMemory::where('name', 'ILIKE', '%'.trim($request->input('q')).'%')->get();
                $items = paginate($items, $size, $page, $type);
                break;
            case "storage-devices":
                $items = StorageDevice::where('name', 'ILIKE', '%'.trim($request->input('q')).'%')->get();
                $items = paginate($items, $size, $page, $type);
                break;
            case "graphic-cards":
                $items = GraphicCard::where('name', 'ILIKE', '%'.trim($request->input('q')).'%')->get();
                $items = paginate($items, $size, $page, $type);
                break;
            case "power-supplies":
                $items = PowerSupply::where('name', 'ILIKE', '%'.trim($request->input('q')).'%')->get();
                $items = paginate($items, $size, $page, $type);
                break;
            case "machines":
                $items = Machine::where('name', 'ILIKE', '%'.trim($request->input('q')).'%')->get();
                $items = paginate($items, $size, $page, $type);
                break;
            case "brands":
                $items = Brand::where('name', 'ILIKE', '%'.trim($request->input('q')).'%')->get();
                $items = array_chunk($items, $size)[$page];
                foreach ($items as $value) {
                    $value->toArray();
                }
                break;
            default:
                return response(["message" => "Not Found"], 404)->header('Content-Type', 'application/json');
        }
        return response($items, 200)->header('Content-Type', 'application/json');
    }

    private function paginate($items, $size, $page, $type) {
        $items = array_chunk($items, $size)[$page];
        foreach ($items as $value) {
            relations($value->toArray(), $type);
        }
        return $items;
    }
    
    private function relations($value, $type) {
        switch($type) {
            case "machines":
                $storageDevices = MachineHasStorageDevice::where('machineId', $value->id)->get();
                foreach ($storageDevices as $storageDevice) {
                    $storageDevice->toArray();
                    $storageDevice["storageDevice"] = relations(StorageDevice::where($storageDevice["storageDeviceId"]), "fff");
                }
                $value["motherboard"] = relations(Brand::where('id', $value["motherboardId"])->get()->first(), "motherboards");
                $value["processor"] = relations(Processor::where('id', $value["processorId"])->get()->first(), "processors");
                $value["ramMemory"] = relations(RAMMemory::where('id', $value["ramMemoryId"])->get()->first(), "ram-memories");
                $value["graphicCard"] = relations(GraphicCard::where('id', $value["graphicCardId"])->get()->first(), "fff");
                $value["storageDevices"] = $storageDevices;
                break;
            case "motherboards":
                $value["brand"] = Brand::where('id', $value["brandId"]);
                $value["socketType"] = SocketType::where('id', $value["socketTypeId"]);
                $value["ramMemoryType"] = RAMMemoryType::where('id', $value["ramMemoryTypeId"]);
                break;
            case "processors":
                $value["brand"] = Brand::where('id', $value["brandId"]);
                $value["socketType"] = SocketType::where('id', $value["socketTypeId"]);
                break;
            case "ram-memories":
                $value["brand"] = Brand::where('id', $value["brandId"]);
                $value["ramMemoryType"] = RAMMemoryType::where('id', $value["ramMemoryTypeId"]);
                break;
            default:
                $value["brand"] = Brand::where('id', $value["brandId"]);
        }
        return $value;
    }

    public function create($request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'imageUrl' => 'required',
            'motherboardId' => 'required',
            'powerSupplyId' => 'required',
            'processorId' => 'required',
            'ramMemoryId' => 'required',
            'ramMemoryAmount' => 'required',
            'storageDevices' => 'required',
            'graphicCardId' => 'required',
            'graphicCardAmount' => 'required',
        ], $messages = validatorMessages());
        if ($validator->fails()) {
            return convertValidator($validator);
        }#'name', 'description', 'imageUrl', 'motherboardId', 'processorId', 'ramMemoryId', 'ramMemoryAmount', 'graphicCardId', 'graphicCardAmount', 'powerSupplyId'
        $body = $request->all();
        $machine = Machine::create($body);
        foreach ($request->input('storageDevices') as $value) {
            MachineHasStorageDevice::create(["machineId" => $machine->id, "storageDeviceId" => $value["storageDeviceId"], "amount" => $value["amount"]]);
        }
        return response([], 200)->header('Content-Type', 'application/json');
    }
}
