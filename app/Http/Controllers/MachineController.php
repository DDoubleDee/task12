<?php

namespace App\Http\Controllers;
 
use Storage;
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
        switch ($type) { # get appropriate data
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
    
    public function search(Request $request, $type, $q)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required',
        ], $messages = validatorMessages());
        if ($validator->fails()) {
            return convertValidator($validator);
        }
        $size = $request->input('pageSize') || 10;
        $page = $request->input('page') || 1;
        switch ($type) { # get appropriate data
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

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [ # check input
            'name' => 'required',
            'imageBase64' => 'required',
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
        }
        $body = $request->all();
        $mboard = Motherboard::where('id', $body["motherboardId"])->get()->first(); # get all parts
        $cpu = Processor::where('id', $body["processorId"])->get()->first(); # get all parts
        $RAM = RAMMemory::where('id', $body["ramMemoryId"])->get()->first(); # get all parts
        $gpu = GraphicCard::where('id', $body["graphicCardId"])->get()->first(); # get all parts
        $psup = PowerSupply::where('id', $body["powerSupplyId"])->get()->first(); # get all parts
        $m2 = 0;
        $sata = 0;
        foreach ($request->input('storageDevices') as $value) { # count storage devices
            $store = StorageDevice::where('id', $value["storageDeviceId"])->get()->first();
            if($store->storageDeviceInterface == "m2"){
                $m2 = $m2 + $value["amount"];
            } else {
                $sata = $sata + $value["amount"];
            }
        }
        $incompatibilities = incompatibility_check($mboard, $cpu, $RAM, $body["ramMemoryAmount"], $body["graphicCardAmount"], $sata, $m2, $gpu, $psup);
        if($incompatibilities != []) { # check for incompatibility
            return response($incompatibilities, 400)->header('Content-Type', 'application/json');
        }
        $image = explode(',', $body["imageBase64"]);
        $ext = explode(';', explode('/', $image[0])[1])[0];
        if($ext != "png" || $ext != "jpeg" || $ext != "jpg"){
            return response(["message" => "please use jpg, png or jpeg in imageBase64"], 400)->header('Content-Type', 'application/json');
        }
        Storage::put(strval($machine->id)."7143143.".$ext, base64_decode($image[1])); # store the image
        $body['imageUrl'] = strval($machine->id)."7143143";
        $machine = Machine::create($body); # create machine
        foreach ($request->input('storageDevices') as $value) { # create storage devices
            MachineHasStorageDevice::create(["machineId" => $machine->id, "storageDeviceId" => $value["storageDeviceId"], "amount" => $value["amount"]]);
        }
        return response([relations($machine->toArray(), "machines")], 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $id) {
        $machine = Machine::where('id', intval($id))->get()->first(); # get machine
        if(is_null($machine)){
            return response(["message" => "machine model not found"], 404)->header('Content-Type', 'application/json');
        }
        $machine->name = $request->input('name') || $machine->name; # update data
        $machine->motherboardId = $request->input('motherboardId') || $machine->motherboardId; # update data
        $machine->powerSupplyId = $request->input('powerSupplyId') || $machine->powerSupplyId; # update data
        $machine->processorId = $request->input('processorId') || $machine->processorId; # update data
        $machine->ramMemoryId = $request->input('ramMemoryId') || $machine->ramMemoryId; # update data
        $machine->ramMemoryAmount = $request->input('ramMemoryAmount') || $machine->ramMemoryAmount; # update data
        $machine->graphicCardId = $request->input('graphicCardId') || $machine->graphicCardId; # update data
        $machine->graphicCardAmount = $request->input('graphicCardAmount') || $machine->graphicCardAmount; # update data
        $m2 = 0;
        $sata = 0;
        if(is_null($request->input('storageDevices'))){ # check storage devices
            foreach (MachineHasStorageDevice::where("machineId", $machine->id)->get() as $value) {
                $store = StorageDevice::where('id', $value->storageDeviceId)->get()->first();
                if($store->storageDeviceInterface == "m2"){
                    $m2 = $m2 + $value->amount;
                } else {
                    $sata = $sata + $value->amount;
                }
            }
        } else {
            foreach ($request->input('storageDevices') as $value) {
                $store = StorageDevice::where('id', $value["storageDeviceId"])->get()->first();
                if($store->storageDeviceInterface == "m2"){
                    $m2 = $m2 + $value["amount"];
                } else {
                    $sata = $sata + $value["amount"];
                }
            }
        }
        $incompatibilities = incompatibility_check(Motherboard::where('id', $machine->processorId)->get()->first(), Processor::where('id', $machine->processorId)->get()->first(), RAMMemory::where('id', $machine->ramMemoryId)->get()->first(), $machine->ramMemoryAmount, $machine->graphicCardAmount, $sata, $m2, GraphicCard::where('id', $machine->graphicCardId)->get()->first(), PowerSupply::where('id', $machine->powerSupplyId)->get()->first());
        if($incompatibilities != []) { # check for incompatibility
            return response($incompatibilities, 400)->header('Content-Type', 'application/json');
        }
        if(!is_null($request->input('imageBase64'))){
            $image = explode(',', $request->input('imageBase64'));
            $ext = explode(';', explode('/', $image[0])[1])[0];
            if($ext != "png" || $ext != "jpeg" || $ext != "jpg"){
                return response(["message" => "please use jpg, png or jpeg in imageBase64"], 400)->header('Content-Type', 'application/json');
            }
            Storage::delete([strval($machine->id)."7143143.png", strval($machine->id)."7143143.jpeg", strval($machine->id)."7143143.jpg"]);
            Storage::put(strval($machine->id)."7143143.".$ext, base64_decode($image[1])); # update image
            $machine->imageUrl = strval($machine->id)."7143143";
        }
        $machine = $machine->save();
        if(!is_null($request->input('storageDevices'))){
            foreach (MachineHasStorageDevice::where("machineId", $machine->id)->get() as $value) {
                $value->delete();
            }
            foreach ($request->input('storageDevices') as $value) {
                MachineHasStorageDevice::create(["machineId" => $machine->id, "storageDeviceId" => $value["storageDeviceId"], "amount" => $value["amount"]]);
            }
        }
        return response([relations($machine->toArray(), "machines")], 200)->header('Content-Type', 'application/json');
    }

    public function delete(Request $request, $id) {
        $machine = Machine::where('id', intval($id))->get()->first();
        if(is_null($machine)){
            return response(["message" => "machine model not found"], 404)->header('Content-Type', 'application/json');
        }
        foreach (MachineHasStorageDevice::where("machineId", $machine->id)->get() as $value) {
            $value->delete();
        }
        Storage::delete([strval($machine->id)."7143143.png", strval($machine->id)."7143143.jpeg", strval($machine->id)."7143143.jpg"]);
        $machine->delete();
        return response(null, 204);
    }

    public function check(Request $request) {
        $validator = Validator::make($request->all(), [
            'motherboardId' => 'required',
            'powerSupplyId' => 'required',
        ], $messages = validatorMessages());
        if ($validator->fails()) {
            return convertValidator($validator);
        }
        $mboard = Motherboard::where('id', $request->input('motherboardId'))->get()->first();
        $psup = PowerSupply::where('id', $request->input('powerSupplyId'))->get()->first();
        $ramMemoryAmount = null;
        $graphicCardAmount = null
        $sata = null;
        $m2 = null;
        $gpu = null;
        $cpu = null;
        $ram = null;
        if(!is_null($request->input('graphicCardId')) && !is_null($request->input('graphicCardAmount'))){
            $gpu = GraphicCard::where('id', $request->input('graphicCardId'))->get()->first();
            $graphicCardAmount = $request->input('graphicCardAmount');
        }
        if(!is_null($request->input('ramMemoryId')) && !is_null($request->input('ramMemoryAmount'))){
            $ram = RAMMemory::where('id', $request->input('ramMemoryId'))->get()->first();
            $graphicCardAmount = $request->input('ramMemoryAmount');
        }
        if(!is_null($request->input('processorId'))){
            $cpu = Processor::where('id', $request->input('processorId'))->get()->first();
        }
        $inc = incompatibility_check($mboard, $cpu, $ram, $ramMemoryAmount, $graphicCardAmount, $sata, $m2, $gpu, $psup);
        if($inc != []){
            return response($inc, 400)->header('Content-Type', 'application/json');
        }
        return response(["message" => "Valid machine"], 200)->header('Content-Type', 'application/json');
    }
    
    public function image(Request $request, $id) {
        $image = Storage::get($id.".png");
        $ext = "png";
        if(is_null($image)){
            $image = Storage::get($id.".jpeg");
            $ext = "jpeg";
        }
        if(is_null($image)){
            $image = Storage::get($id.".jpg");
            $ext = "jpg";
        }
        if(is_null($image)){
            return response(["message" => "image not found"], 404)->header('Content-Type', 'application/json');
        }
        return response($image, 200)->header('Content-Type', 'image/'.$ext);
    }

    private function incompatibility_check($mboard, $cpu, $ram, $ramMemoryAmount, $graphicCardAmount, $sata, $m2, $gpu, $psup) {
        $incompatibilities = [];
        if(!is_null($cpu)){
            if($mboard->socketTypeId != $cpu->socketTypeId){
                $incompatibilities["socket type"] = "Chosen motherboard is incompatible with chosen CPU due to a different socket type";;
            }
            if($mboard->maxTdp < $cpu->tdp){
                $incompatibilities["max tdp"] = "Chosen motherboard's max tdp is lower than chosen CPU's tdp";
            }
        }
        if(!is_null($ram)){
            if($mboard->ramMemoryTypeId != $ram->ramMemoryTypeId){
                $incompatibilities["ram type"] = "Chosen motherboard is incompatible with the chosen RAM card due to a different RAM type";
            }
            if($mboard->ramMemoryAmount < $ramMemoryAmount){
                $incompatibilities["ram amount"] = "Chosen motherboard does not have enough RAM card slots";
            }
            if($ramMemoryAmount <= 0){
                $incompatibilities["ram amount"] = "A machine must have at least one RAM card";
            }
        }
        if(!is_null($gpu)){
            if($mboard->graphicCardAmount < $graphicCardAmount){
                $incompatibilities["gpu amount"] = "Chosen motherboard does not have enough PCI Express slots";
            }
            if($graphicCardAmount <= 0){
                $incompatibilities["ram amount"] = "A machine must have at least one graphic card";
            }
            if($graphicCardAmount > 1 && $gpu->supportMultiGpu == 0){
                $incompatibilities["lack of sli/crossfire"] = "There can be no more than 1 of chosen graphic card due to lack of SLI/Crossfire support";
            }
            if($graphicCardAmount * $gpu->minimumPowerSupply < $psup->potency){
                $incompatibilities["power supply"] = "Chosen power supply cannot power chosen graphic card or their amount";
            }
        }
        if(!is_null($sata) && !is_null($m2)){
            if($mboard->sataSlots < $sata){
                $incompatibilities["sata amount"] = "Chosen motherboard does not have enough SATA slots";
            }
            if($mboard->m2Slots < $m2){
                $incompatibilities["m2 amount"] = "Chosen motherboard does not have enough M2 slots";
            }
            if($m2 + $sata <= 0){
                $incompatibilities["storage amount"] = "A machine must have at least one storage device";
            }
        }
        return $incompatibilities;
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
}
