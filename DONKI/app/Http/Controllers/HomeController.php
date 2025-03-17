<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    private $apiKey;


   

    private function ObtenerDatos($endpoint)
    {
        $response = Http::withoutVerifying()->get("http://api.nasa.gov/DONKI/{$endpoint}", [
            'api_key' => 'PorclFpR4H7hYyoRDwhMFpD62ZpICSSbRYKgaNOx',
        ]);

       // $response = Http::withoutVerifying()->get("http://api.nasa.gov/DONKI/{$endpoint}", [
       //      'api_key' => 'DEMO_KEY',
       //  ]);

        return $response->json();
    }

    public function ObtenerInstrumetos()
    {
     
      $endpoints = ['CME','GST','FLR','SEP','MPC','RBE','HSS'];      
      $instrumentos = []; 

      foreach ($endpoints as $endpoint) {
      $datos = $this->ObtenerDatos($endpoint);     
      foreach ($datos as $dato) {    
            if (isset($dato['instruments'])) {
                  foreach ($dato['instruments'] as $intrumento) {
                        if (isset($intrumento['displayName'])) {
                              $instrumentos[] = $intrumento['displayName'];
                              }
                        }
                  }
            }
      }
            return response()->json(array_values(array_unique($instrumentos)));
      }


      public function IdActividades()
      {
      $endpoints = ['CME','GST','FLR','SEP','MPC','RBE','HSS'];
      $id_Actividades = []; 
      foreach ($endpoints as $endpoint) {
      $datos = $this->ObtenerDatos($endpoint);     
      foreach ($datos as $dato) {  
            if (isset($dato['linkedEvents'])) {  
                  foreach ($dato['linkedEvents'] as $actividad) {
                        $actividadCompleto = explode('-', $actividad['activityID']);
                        $id_Actividades[] = $actividadCompleto[3].'-'.$actividadCompleto[4];
                        }
                  }
            }
      }

      return response()->json(array_values(array_unique($id_Actividades)));
      }





      
      public function ObtenerPorNombre($nombreInstrumento)
      {

      $endpoints = ['CME','GST','FLR','SEP','MPC','RBE','HSS'];
      $conteoInstrumentos = 0;
      $TotalIntrumentos = 0;

      foreach ($endpoints as $endpoint) {
      $datos = $this->ObtenerDatos($endpoint);     
      foreach ($datos as $dato) {  
            if (isset($dato['instruments'])) {
                  foreach ($dato['instruments'] as $instrumento) {
                        if ($instrumento['displayName'] === $nombreInstrumento) {
                        $conteoInstrumentos++;
                        }
                  $TotalIntrumentos++;
                        }
                  }        
            }
      }
            if ($TotalIntrumentos > 0)
            {
              $porcentaje = $conteoInstrumentos / $TotalIntrumentos;
              $porcentaje = $porcentaje*100;
              $porcentaje = $porcentaje.'%';
            }
            else
            {
              $porcentaje ='No encontrado 0%';
            }

dd($conteoInstrumentos.'-'.$TotalIntrumentos);
      return response()->json($porcentaje);
      }






 

    

     

}
