<?php

namespace App\Http\Controllers;
 
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        $size = $request->input('pageSize');
        if(is_null($size)){
            $size = 10;
        }
        $page = $request->input('page');
        if(is_null($page)){
            $page = 1;
        }
        switch ($type) { # get appropriate data
            case "motherboards":
                $items = Motherboard::get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "processors":
                $items = Processor::get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "ram-memories":
                $items = RAMMemory::get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "storage-devices":
                $items = StorageDevice::get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "graphic-cards":
                $items = GraphicCard::get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "power-supplies":
                $items = PowerSupply::get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "machines":
                $items = Machine::get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "brands":
                $items = Brand::get()->toArray();
                $items = array_chunk($items, $size);
                if(array_key_exists($page - 1, $items)){
                    $items = $items[$page - 1];
                }else{
                    $items = [];
                }
                break;
            default:
                return response(["message" => "Not Found"], 404)->header('Content-Type', 'application/json');
        }
        return response($items, 200)->header('Content-Type', 'application/json');
    }
    
    public function search(Request $request, $type)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required',
        ], $messages = Controller::validatorMessages());
        if ($validator->fails()) {
            return Controller::convertValidator($validator);
        }
        $size = $request->input('pageSize');
        if(is_null($size)){
            $size = 10;
        }
        $page = $request->input('page');
        if(is_null($page)){
            $page = 1;
        }
        switch ($type) { # get appropriate data
            case "motherboards":
                $items = Motherboard::where('name', 'like', "%".trim($request->input('q'))."%")->get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "processors":
                $items = Processor::where('name', 'like', "%".trim($request->input('q'))."%")->get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "ram-memories":
                $items = RAMMemory::where('name', 'like', "%".trim($request->input('q'))."%")->get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "storage-devices":
                $items = StorageDevice::where('name', 'like', "%".trim($request->input('q'))."%")->get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "graphic-cards":
                $items = GraphicCard::where('name', 'like', "%".trim($request->input('q'))."%")->get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "power-supplies":
                $items = PowerSupply::where('name', 'like', "%".trim($request->input('q'))."%")->get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "machines":
                $items = Machine::where('name', 'like', "%".trim($request->input('q'))."%")->get();
                $items = MachineController::paginate($items, $size, $page, $type);
                break;
            case "brands":
                $items = Brand::where('name', 'like', "%".trim($request->input('q'))."%")->get()->toArray();
                $items = array_chunk($items, $size);
                if(array_key_exists($page - 1, $items)){
                    $items = $items[$page - 1];
                }else{
                    $items = [];
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
        ], $messages = Controller::validatorMessages());
        if ($validator->fails()) {
            return Controller::convertValidator($validator);
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
        $incompatibilities = MachineController::incompatibility_check($mboard, $cpu, $RAM, $body["ramMemoryAmount"], $body["graphicCardAmount"], $sata, $m2, $gpu, $psup);
        if($incompatibilities != []) { # check for incompatibility
            return response($incompatibilities, 400)->header('Content-Type', 'application/json');
        }
        $image = explode(',', $body["imageBase64"]);
        $ext = explode(';', explode('/', $image[0])[1])[0];
        if($ext != "png" && $ext != "jpeg" && $ext != "jpg"){
            return response(["message" => "please use jpg, png or jpeg in imageBase64"], 400)->header('Content-Type', 'application/json');
        }
        $body['imageUrl'] = "7143143";
        $body['description'] = $request->input('description');
        if(is_null($body['description'])){
            $body['description'] = "";
        }
        $machine = Machine::create($body); # create machine
        Storage::put(strval($machine->id)."7143143.".$ext, base64_decode($image[1])); # store the image
        $machine->imageUrl = strval($machine->id)."7143143";
        $machine->save();
        foreach ($request->input('storageDevices') as $value) { # create storage devices
            MachineHasStorageDevice::create(["machineId" => $machine->id, "storageDeviceId" => $value["storageDeviceId"], "amount" => $value["amount"]]);
        }
        return response([MachineController::relations($machine->toArray(), "machines")], 200)->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $id) {
        $machine = Machine::where('id', intval($id))->get()->first(); # get machine
        if(is_null($machine)){
            return response(["message" => "machine model not found"], 404)->header('Content-Type', 'application/json');
        }
        if(!is_null($request->input('name'))){
            $machine->name = $request->input('name');
        }
        if(!is_null($request->input('motherboardId'))){
            $machine->motherboardId = $request->input('motherboardId');
        }
        if(!is_null($request->input('powerSupplyId'))){
            $machine->powerSupplyId = $request->input('powerSupplyId');
        }
        if(!is_null($request->input('processorId'))){
            $machine->processorId = $request->input('processorId');
        }
        if(!is_null($request->input('ramMemoryId'))){
            $machine->ramMemoryId = $request->input('ramMemoryId');
        }
        if(!is_null($request->input('ramMemoryAmount'))){
            $machine->ramMemoryAmount = $request->input('ramMemoryAmount');
        }
        if(!is_null($request->input('graphicCardId'))){
            $machine->graphicCardId = $request->input('graphicCardId');
        }
        if(!is_null($request->input('graphicCardAmount'))){
            $machine->graphicCardAmount = $request->input('graphicCardAmount');
        }
        if(!is_null($request->input('description'))){
            $machine->description = $request->input('description');
        }
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
        $incompatibilities = MachineController::incompatibility_check(Motherboard::where('id', $machine->processorId)->get()->first(), Processor::where('id', $machine->processorId)->get()->first(), RAMMemory::where('id', $machine->ramMemoryId)->get()->first(), $machine->ramMemoryAmount, $machine->graphicCardAmount, $sata, $m2, GraphicCard::where('id', $machine->graphicCardId)->get()->first(), PowerSupply::where('id', $machine->powerSupplyId)->get()->first());
        if($incompatibilities != []) { # check for incompatibility
            return response($incompatibilities, 400)->header('Content-Type', 'application/json');
        }
        if(!is_null($request->input('imageBase64'))){
            $image = explode(',', $request->input('imageBase64'));
            $ext = explode(';', explode('/', $image[0])[1])[0];
            if($ext != "png" && $ext != "jpeg" && $ext != "jpg"){
                return response(["message" => "please use jpg, png or jpeg in imageBase64"], 400)->header('Content-Type', 'application/json');
            }
            Storage::delete([strval($machine->id)."7143143.png", strval($machine->id)."7143143.jpeg", strval($machine->id)."7143143.jpg"]);
            Storage::put(strval($machine->id)."7143143.".$ext, base64_decode($image[1])); # update image
            $machine->imageUrl = strval($machine->id)."7143143";
        }
        $machine->save();
        if(!is_null($request->input('storageDevices'))){
            DB::table('machinehasstoragedevice')->where('machineId', '=', $machine->id)->delete();
            foreach ($request->input('storageDevices') as $value) {
                MachineHasStorageDevice::create(["machineId" => $machine->id, "storageDeviceId" => $value["storageDeviceId"], "amount" => $value["amount"]]);
            }
        }
        return response([MachineController::relations($machine->toArray(), "machines")], 200)->header('Content-Type', 'application/json');
    }

    public function delete(Request $request, $id) {
        $machine = Machine::where('id', intval($id))->get()->first();
        if(is_null($machine)){
            return response(["message" => "machine model not found"], 404)->header('Content-Type', 'application/json');
        }
        DB::table('machinehasstoragedevice')->where('machineId', '=', $machine->id)->delete();
        Storage::delete([strval($machine->id)."7143143.png", strval($machine->id)."7143143.jpeg", strval($machine->id)."7143143.jpg"]);
        $machine->delete();
        return response(null, 204);
    }

    public function check(Request $request) {
        $validator = Validator::make($request->all(), [ # check inputs
            'motherboardId' => 'required', 
            'powerSupplyId' => 'required',
        ], $messages = Controller::validatorMessages());
        if ($validator->fails()) {
            return Controller::convertValidator($validator);
        }
        $mboard = Motherboard::where('id', $request->input('motherboardId'))->get()->first(); # get starting data
        $psup = PowerSupply::where('id', $request->input('powerSupplyId'))->get()->first(); # get starting data
        $ramMemoryAmount = null; # get starting data
        $graphicCardAmount = null; # get starting data
        $sata = null; # get starting data
        $m2 = null; # get starting data
        $gpu = null; # get starting data
        $cpu = null; # get starting data
        $ram = null; # get starting data
        if(!is_null($request->input('graphicCardId')) && !is_null($request->input('graphicCardAmount'))){ # get parts data
            $gpu = GraphicCard::where('id', $request->input('graphicCardId'))->get()->first();
            $graphicCardAmount = $request->input('graphicCardAmount');
        }
        if(!is_null($request->input('ramMemoryId')) && !is_null($request->input('ramMemoryAmount'))){ # get parts data
            $ram = RAMMemory::where('id', $request->input('ramMemoryId'))->get()->first();
            $ramMemoryAmount = $request->input('ramMemoryAmount');
        }
        if(!is_null($request->input('processorId'))){ # get parts data
            $cpu = Processor::where('id', $request->input('processorId'))->get()->first();
        }
        if(!is_null($request->input('storageDevices'))){
            $m2 = 0;
            $sata = 0;
            foreach ($request->input('storageDevices') as $value) {
                $store = StorageDevice::where('id', $value["storageDeviceId"])->get()->first();
                if($store->storageDeviceInterface == "m2"){
                    $m2 = $m2 + $value["amount"];
                } else {
                    $sata = $sata + $value["amount"];
                }
            }
        }
        $inc = MachineController::incompatibility_check($mboard, $cpu, $ram, $ramMemoryAmount, $graphicCardAmount, $sata, $m2, $gpu, $psup);
        if($inc != []){ # check incompatibility
            return response($inc, 400)->header('Content-Type', 'application/json');
        }
        return response(["message" => "Valid machine"], 200)->header('Content-Type', 'application/json');
    }
    
    public function image(Request $request, $id) {
        $image = Storage::get($id.".png");
        $ext = "png";
        if(is_null($image)){ # get correct image with extension
            $image = Storage::get($id.".jpeg");
            $ext = "jpeg";
        }
        if(is_null($image)){ # get correct image with extension
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
        if(!is_null($cpu)){ # check cpu compatibility
            if($mboard->socketTypeId != $cpu->socketTypeId){
                $incompatibilities["socket type"] = "Chosen motherboard is incompatible with chosen CPU due to a different socket type";;
            }
            if($mboard->maxTdp < $cpu->tdp){
                $incompatibilities["max tdp"] = "Chosen motherboard's max tdp is lower than chosen CPU's tdp";
            }
        }
        if(!is_null($ram)){ # check ram compatibility
            if($mboard->ramMemoryTypeId != $ram->ramMemoryTypeId){
                $incompatibilities["ram type"] = "Chosen motherboard is incompatible with the chosen RAM card due to a different RAM type";
            }
            if($mboard->ramMemorySlots < $ramMemoryAmount){
                $incompatibilities["ram amount"] = "Chosen motherboard does not have enough RAM card slots";
            }
            if($ramMemoryAmount <= 0){
                $incompatibilities["ram amount"] = "A machine must have at least one RAM card";
            }
        }
        if(!is_null($gpu)){ # cehck gpu compatibility
            if($mboard->pciSlots < $graphicCardAmount){
                $incompatibilities["gpu amount"] = "Chosen motherboard does not have enough PCI Express slots";
            }
            if($graphicCardAmount <= 0){
                $incompatibilities["ram amount"] = "A machine must have at least one graphic card";
            }
            if($graphicCardAmount > 1 && $gpu->supportMultiGpu == 0){
                $incompatibilities["lack of sli/crossfire"] = "There can be no more than 1 of chosen graphic card due to lack of SLI/Crossfire support";
            }
            if($graphicCardAmount * $gpu->minimumPowerSupply > $psup->potency){
                $incompatibilities["power supply"] = "Chosen power supply cannot power chosen graphic card or their amount";
            }
        }
        if(!is_null($sata) && !is_null($m2)){ # check storage device compatibility
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
        if(!is_array($items)){
            $items = $items->toArray();
        }
        $items = array_chunk($items, $size);
        if(!array_key_exists($page - 1, $items)){
            return [];
        }else{
            $items = $items[$page - 1];
        }
        foreach ($items as $key => $value) { # load relations for items
            $value = MachineController::relations($value, $type);
            $items[$key] = $value;
        }
        return $items;
    }
    
    private function relations($value, $type) {
        switch($type) {
            case "machines":
                $storageDevices = MachineHasStorageDevice::where('machineId', trim($value["id"]))->get();
                foreach ($storageDevices as $storageDevice) { # get storage devices
                    $storageDevice->toArray();
                    $storageDevice["storageDevice"] = MachineController::relations(StorageDevice::where('id', $storageDevice["storageDeviceId"])->get()->first()->toArray(), "fff");
                    unset($storageDevice["storageDeviceId"]);
                }
                $value["motherboard"] = MachineController::relations(Motherboard::where('id', $value["motherboardId"])->get()->first()->toArray(), "motherboards");
                $value["processor"] = MachineController::relations(Processor::where('id', $value["processorId"])->get()->first()->toArray(), "processors");
                $value["ramMemory"] = MachineController::relations(RAMMemory::where('id', $value["ramMemoryId"])->get()->first()->toArray(), "ram-memories");
                $value["graphicCard"] = MachineController::relations(GraphicCard::where('id', $value["graphicCardId"])->get()->first()->toArray(), "fff");
                $value["powerSupply"] = MachineController::relations(PowerSupply::where('id', $value["powerSupplyId"])->get()->first()->toArray(), "fff");
                $value["storageDevices"] = $storageDevices;
                unset($value["motherboardId"]);
                unset($value["processorId"]);
                unset($value["ramMemoryId"]);
                unset($value["graphicCardId"]);
                unset($value["powerSupplyId"]);
                return $value;
            case "motherboards":
                $value["brand"] = Brand::where('id', $value["brandId"])->get()->first()->toArray();
                $value["socketType"] = SocketType::where('id', $value["socketTypeId"])->get()->first()->toArray();
                $value["ramMemoryType"] = RAMMemoryType::where('id', $value["ramMemoryTypeId"])->get()->first()->toArray();
                unset($value["brandId"]);
                unset($value["socketTypeId"]);
                unset($value["ramMemoryTypeId"]);
                return $value;
            case "processors":
                $value["brand"] = Brand::where('id', $value["brandId"])->get()->first()->toArray();
                $value["socketType"] = SocketType::where('id', $value["socketTypeId"])->get()->first()->toArray();
                unset($value["brandId"]);
                unset($value["socketTypeId"]);
                return $value;
            case "ram-memories":
                $value["brand"] = Brand::where('id', $value["brandId"])->get()->first()->toArray();
                $value["ramMemoryType"] = RAMMemoryType::where('id', $value["ramMemoryTypeId"])->get()->first()->toArray();
                unset($value["brandId"]);
                unset($value["ramMemoryTypeId"]);
                return $value;
            default:
                $value["brand"] = Brand::where('id', $value["brandId"])->get()->first()->toArray();
                unset($value["brandId"]);
                return $value;
        }
    }
}
