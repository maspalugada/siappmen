<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Unit;

class MasterUnit extends Component
{
    public $units;
    public $name, $location, $unitId;
    public $isEdit = false;

    public function mount()
    {
        $this->loadUnits();
    }

    public function loadUnits()
    {
        $this->units = Unit::orderBy('id', 'asc')->get();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->location = '';
        $this->unitId = null;
        $this->isEdit = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        if ($this->isEdit) {
            $unit = Unit::find($this->unitId);
            $unit->update([
                'name' => $this->name,
                'location' => $this->location,
            ]);
            session()->flash('message', 'Data unit berhasil diperbarui.');
        } else {
            Unit::create([
                'name' => $this->name,
                'location' => $this->location,
            ]);
            session()->flash('message', 'Data unit berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadUnits();
    }

    public function edit($id)
    {
        $unit = Unit::find($id);
        $this->unitId = $unit->id;
        $this->name = $unit->name;
        $this->location = $unit->location;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        Unit::find($id)->delete();
        session()->flash('message', 'Data unit berhasil dihapus.');
        $this->loadUnits();
    }

    public function render()
    {
        return view('livewire.master-unit')->layout('layouts.siappmen');
    }
}
