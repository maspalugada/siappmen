<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Instrument;
use App\Models\Unit;

class MasterInstrument extends Component
{
    public $instruments, $units;
    public $code, $name, $description, $unit_id, $status = 'available', $instrumentId;
    public $isEdit = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->instruments = Instrument::with('unit')->orderBy('id', 'asc')->get();
        $this->units = Unit::all();
    }

    public function resetForm()
    {
        $this->code = $this->name = $this->description = '';
        $this->status = 'available';
        $this->unit_id = null;
        $this->isEdit = false;
        $this->instrumentId = null;
    }

    public function save()
    {
        $this->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'unit_id' => 'nullable|integer',
        ]);

        if ($this->isEdit) {
            $instrument = Instrument::find($this->instrumentId);
            $instrument->update([
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'unit_id' => $this->unit_id,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Instrumen berhasil diperbarui.');
        } else {
            Instrument::create([
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'unit_id' => $this->unit_id,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Instrumen baru berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadData();
    }

    public function edit($id)
    {
        $item = Instrument::find($id);
        $this->instrumentId = $item->id;
        $this->code = $item->code;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->unit_id = $item->unit_id;
        $this->status = $item->status;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        Instrument::find($id)->delete();
        session()->flash('message', 'Instrumen berhasil dihapus.');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.master-instrument');
    }
}
