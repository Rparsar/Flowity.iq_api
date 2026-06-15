<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';

    protected $fillable = ['suscriptible_id', 'suscriptible_type', 'tipo_periodo', 'cantidad_periodos', 'estado'];

    protected $casts = [];

    public function suscriptible()
    {
        return $this->morphTo();
    }

    // TODO: Refactorizar métodos de cálculo de próximo pago sin fecha_proximo_pago
    // public function calcularProximoPago()
    // {
    //     $fecha = $this->fecha_proximo_pago;
    //     $periodo = $this->tipo_periodo;
    //     $cantidad = $this->cantidad_periodos;
    //
    //     return match($periodo) {
    //         'dia' => $fecha->addDays($cantidad),
    //         'semana' => $fecha->addWeeks($cantidad),
    //         'mes' => $fecha->addMonths($cantidad),
    //         'año' => $fecha->addYears($cantidad),
    //         default => $fecha,
    //     };
    // }
    //
    // public function renovar()
    // {
    //     $this->fecha_proximo_pago = $this->calcularProximoPago();
    //     $this->save();
    // }

    public function cancelar()
    {
        $this->estado = 'cancelada';
        $this->save();
    }
}
