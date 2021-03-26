<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class UsersController extends Component
{

    use WithPagination;

    //propiedades publicas
    public $username;
    public $selected_id, $search;
    public $action = 1; // permite mover entre formularios
    private $pagination = 5;

    // Se ejecuta antes del render
    public function mount(){

    }

    // se ejecuta despues del mount
    public function render()
    {
        if (strlen($this->search) > 0) {
            $info = User::where('username', 'like' .'%'. $this->search .'%')->pagination($this->pagination);
            return view('livewire.usuarios.component', [
                'info' => $info,
            ]);
        }
        else {
            $info = User::pagination($this->pagination)->orderBy('username', 'DESC'); //
            return view('livewire.usuarios.component', [
                'info' => $info,
            ]);
        }
    }

    // para busqueda con paginacion
    public function updatingSearch(): void
    {
        $this->gotoPage(1);
    }

    //moverse entre ventanas
    public function doAction($action){
        $this->action = $action;
    }

    //limpiar propiedades
    public function resetInput(){
        $this->username = '';
        $this->selected_id = null;
        $this->action = 1;
        $this->search = '';
    }

    // Mostrar informacion del resgistro
    public function edit($id){
        $record = User::findOrFail($id);
        $this->username = $record->username;
        $this->selected_id = $record->id;
        $this->action = 2;  //->1 mostrar tabla, 2=consultar formulario
    }

    // create/update
    public function storeOrUpdate(){
        //validar los campos
        $this->validate([
            'username' => 'required'
        ]);

        //valida si existe otro registro con el mismo username
        if ($this->selected_id > 0)
        {
            $existe = User::where('username', $this->username)->where('id', '<>', $this->selected_id)
                ->select('username')->get();
            if ($existe->count())
            {
                session()->flash('msg-error', "Ya existe otro registro on el mismmo usuarios!");
                $this->resetInput();
                return;
            }
        }
    }


}
