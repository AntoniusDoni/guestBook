<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Guests;

class Guest extends Component
{
    public $guests, $firstname, $lastname, $organization, $address, $province,$city,$guests_id;
    public $isModal = 0;

    public function render()
    {
        $this->guests= Guests::orderBy('created_at', 'DESC')->get();
        return view('livewire.guest');
    }
    public function create()
    {
        
        $this->resetFields();
        $this->openModal();
    }
    public function closeModal()
    {
        $this->isModal = false;
    }

    //FUNGSI INI DIGUNAKAN UNTUK MEMBUKA MODAL
    public function openModal()
    {
        $this->isModal = true;
    }

    //FUNGSI INI UNTUK ME-RESET FIELD/KOLOM, SESUAIKAN FIELD APA SAJA YANG KAMU MILIKI
    public function resetFields()
    {
        $this->firstname = '';
        $this->lastname = '';
        $this->address = '';
        $this->organization = '';
        $this->organization = '';
        $this->province='';
        $this->city='';
        $this->guests_id='';

    }

    //METHOD STORE AKAN MENG-HANDLE FUNGSI UNTUK MENYIMPAN / UPDATE DATA
    public function store()
    {
        //MEMBUAT VALIDASI
        $this->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'organization' => 'required|string',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string'
        ]);
        Guests::updateOrCreate(['id' => $this->guests_id], [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'organization' => $this->organization,
            'address' => $this->address,
            'province' => $this->province,
            'city' => $this->city,
        ]);

        session()->flash('message', $this->guests_id ? $this->firstname . ' Diperbaharui': $this->firstname . ' Ditambahkan');
        $this->closeModal(); //TUTUP MODAL
        $this->resetFields(); //DAN BERSIHKAN FIELD
    }

    //FUNGSI INI UNTUK MENGAMBIL DATA DARI DATABASE BERDASARKAN ID Guests
    public function edit($id)
    {
        $Guests = Guests::find($id); //BUAT QUERY UTK PENGAMBILAN DATA
        //LALU ASSIGN KE DALAM MASING-MASING PROPERTI DATANYA
        $this->guests_id = $id;
        $this->firstname = $Guests->firstname;
        $this->lastname = $Guests->lastname;
        $this->organization = $Guests->organization;
        $this->address = $Guests->address;
        $this->province = $Guests->province;
        $this->city = $Guests->city;
        $this->openModal(); //LALU BUKA MODAL
    }

    //FUNGSI INI UNTUK MENGHAPUS DATA
    public function delete($id)
    {
        $Guests = Guests::find($id); //BUAT QUERY UNTUK MENGAMBIL DATA BERDASARKAN ID
        $Guests->delete(); //LALU HAPUS DATA
        session()->flash('message', $Guests->firstname . ' Dihapus'); //DAN BUAT FLASH MESSAGE UNTUK NOTIFIKASI
    }
}
