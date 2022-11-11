<?php

    /**
     * Autor: Crivera.
     * Se tiene una fecha inicial, desde donde parte la organización.
     * Se tienen unas horas diarias. 
     * Hay que revisar el tema de los festivos... ojo.
     */
    
    $dias[ 0 ] = "D"; 
    $dias[ 1 ] = "L";
    $dias[ 2 ] = "M";
    $dias[ 3 ] = "M";
    $dias[ 4 ] = "J";
    $dias[ 5 ] = "V";
    $dias[ 6 ] = "S";

    $bandera = 0; //Aquí, para los checkbox, el contador nos sirve como bandera.
    $fecha_parametrica = "";
    $tmp_festivo = ""; 

    //Revisamos los checkbox.
    for( $i = 0; $i <= 6; $i ++ )
    {
        if( isset( $_GET[ 'cbox'.$i ] ) ){ $dia_laboral[ $i ] = 1; $bandera ++; }
        else{ $dia_laboral[ $i ] = 0; }
    }

    $cadena_festivos = "";

    $numero_raps = 6;
            
    //if( $bandera <= 0 ) echo "<script>alert( 'Seleccione algún día para trabajar.' );</script>";

    //Si se está haciendo lo peración y al menos se seleccionó un día para trabajar.
    if( isset( $_GET[ 'calcular' ] ) && $bandera > 0 )
    {
        $fecha_como_entero = isset( $_GET[ 'fecha-inicio' ] ) ? strtotime( $_GET[ 'fecha-inicio' ] ): "";    
        $aaaa = isset( $_GET[ 'fecha-inicio' ] ) ? date( "Y", $fecha_como_entero ): ""; 
        $mm = isset( $_GET[ 'fecha-inicio' ] ) ? date( "m", $fecha_como_entero ): ""; 
        $dia = isset( $_GET[ 'fecha-inicio' ] ) ? date( "d", $fecha_como_entero ): ""; //Día cuando empieza la actividad.
        $fecha_parametrica = $aaaa."-".$mm."-".$dia;
        $horas_super_total = 0;
        $tmp_suma_horas = 0;
    
        echo "<h2>Inicio ".$aaaa.$mm.$dia."</h2>";
        if( $aaaa < date("Y") ) echo "<script>alert( 'Por favor escoge una fecha.' );</script>";
    
        for( $i = 1; $i <= $numero_raps; $i ++ ) //Recogemos los datos de las horas
        {
            //if( isset( $_GET[ 'horas'.$i ] ) ) echo $_GET[ 'horas'.$i ]."<br>";            
            $horas_totales_actividad[ $i ] = $_GET[ 'horas'.$i ] * 1;
            $actividades[ $i ] = $horas_totales_actividad[ $i ] != 0 ? $_GET[ 'dato'.$i ]: "";
            $horas_super_total += $_GET[ 'horas'.$i ] * 1;
            $horas_por_dia[ $i ] = $_GET[ 'horas-diarias' ] * 1; 
        }
       
        //Si hay festivos en el campo de texto
        if( isset( $_GET[ 'festivos' ] ) ) $cadena_festivos = $_GET[ 'festivos' ]; 

        echo "<h3>Horas: ".$horas_super_total."</h3>";
        
        $i = 1; //Variables para ciclos.
        $j = 0; //Variables para ciclos.
        $k = 0; //Variables para ciclos.
        $fecha = ""; //Variable temporal, no es parámetro.
        $contador = 0; //Estavariable contará las horas del día.
        $contador_mes = 0;
        $tmp_c = "";
        $todavia = 1;
        
        //echo date( "Y-m-t", strtotime( "2022-10-28" ) )."<br>";
        //echo date( "t", strtotime( "2022-10-28" ) )."<br>";
        
        //$fecha = $j."-$i-01";
        //echo $fecha;
    
        //último dia de este mes.
        //echo date( "Y-m-t", strtotime( $fecha ) )."<br>";
        //echo "<br>";
        
        //Imprimir que día de la semana es.
        //echo date('l-d', strtotime($fecha));
    
        //Imprimir solo el día.
        //echo date( "l" );
        
        while( $todavia == 1 )
        {    
            //Se ingrese cualquier fecha que esté dentro del primer mes de trabajo.
            $fecha = "$aaaa-$mm-$dia";
            echo "<h2>$mm / $aaaa</h2>";
        
            echo "<table border='1px'>";
            echo "<tr>";
            
            //Imprimimos las cabeceras de días.
            for( $i = 0; $i <= 6; $i ++ )
            echo "<td>".$dias[ $i ]."</td>";
                
            echo "</tr>";
            
            $i = 1; //Reinicio $i porque la utilicé en el anteror ciclo.
            //echo date("l-d", strtotime( "2022-10-01" ))."<br>";
        
            echo "<tr>";
            
            //Imprimir los días del mes, de una fecha determinada.
            while( $i <= date( "t", strtotime( $fecha ) ) ) //-----------calendario-----------------------------------
            {
                if( $j < date( "w", strtotime( "$aaaa-$mm-01" )) ) //En qué día de la semana inicia ese mes.
                {
                    echo "<td></td>"; //Espacios vacíos pues no ha iniciado el mes.
                    $j ++;
        
                }else{
                    
                    //Si es día laboral.
                    if( $dia_laboral[ date("w", strtotime( "$aaaa-$mm-$i" )) * 1 ] == 1 && comparar_fechas( $cadena_festivos, $aaaa, $mm, $i ) != true  )
                    {        
                        if( ( $i >= $dia && $contador_mes == 0 ) || $contador_mes >= 1 )
                        {
                            if( $contador < $horas_super_total ) //Días usados u ocupados.
                            {                        
                                $contador += $horas_por_dia[ 1 ];
                                //$tmp_c = "<div style='color: gray;'>".$horas_por_dia[ 0 ]."</div>";
                                $tmp_c = "<div style='color: gray;'>".$contador."h</div>";
                                //$tmp_c = date("w", strtotime( "$aaaa-$mm-$i" ));
                                
                                $tmp_suma_horas = 0;
                            
                                //Se redistribuirá la etiqueta de las actividades.
                                for( $k = 1; $k <= $numero_raps; $k ++ )
                                {
                                    $tmp_suma_horas += $horas_totales_actividad[ $k ];
                                    
                                    //if( $contador <= $tmp_suma_horas || ( abs( $tmp_suma_horas - $contador ) > 0 && abs( $tmp_suma_horas - $contador ) <= $horas_por_dia[ $k ] ) )
                                    if( $contador <= $tmp_suma_horas )
                                    {
                                        $tmp_c .= "<div style='font-size: 8px;'>".$actividades[ $k ]."</div>";
                                        break;
                                    }
                                }
                                
                            }else{
                                
                                $tmp_c = "";
                            }
                        }                    
                        
                    }else{
                        
                        $tmp_c = "";
                    }
        
                    if( comparar_fechas( $cadena_festivos, $aaaa, $mm, $i ) ) 
                    {
                        $tmp_festivo = "background-color: red; color: white; ";

                    }else{

                        $tmp_festivo = "";
                    }

                    //Aquí se coloca el día en el calendario.
                    echo "<td><div style='text-align: center; font-family: Tahoma; font-size: 25px; $tmp_festivo '> ".$i."</div> $tmp_c </td>";
                    $i ++;      
                }
        
                if( ( $j + $i -1 ) % 7 == 0 ) echo "</tr><tr>"; //cuando llega a lunes, salto de línea.
            } //--------------------------------------------------Fin calendario--------------------------------------
            
            echo "</tr>";
            echo "</table>";
        
            //echo "Contador = ".$contador;
            
            //Ojo con esta decisión, detiene el ciclo pero acorde a la del incremento de $contador.
            if( $contador < $horas_super_total ) //Si todavía hay horas.
            {
                $todavia = 1; //Seguimos con la vaina.
                $i = 1;
                $j = 0;
                $mm ++;
                $contador_mes ++;
                
                if( $mm > 12 )
                {
                    $mm = 1;
                    $aaaa ++;
                }
                
            }else{
                
                $todavia = 0; //Terminamos el calendario.
            }
        }
        
        /*echo "<hr>";
        echo date('l-d', strtotime( "2022-10-03" ));
        
        for( $i = 1; $i <= 31; $i ++ )
        echo date("w", strtotime( "2022-10-$i" ));*/
    }
    
?>
<!-- Software elaborado por Crivera. -->
<hr>
<form action="calendario-v1.php" method="_GET">
    
    <p>
        Fecha inicio actividades.
        <input type="date" name="fecha-inicio" value="<?php echo $fecha_parametrica; ?>" min="2022-10-01" required>
        <br>
    </p>
    
    <p>
        Ingrese el o los festivos del período a evaluar. Ejemplo (01/08/2022 25/12/2022) <br>
        <input type="text" name="festivos" value="<?php echo $cadena_festivos ?>" placeholder = "Use números, espacio y /" onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode == 47 || event.charCode == 32)">
    </p>

    <p>
        <?php
            for( $i = 1; $i <= $numero_raps; $i ++ )
            {
        ?>
        
        Rap<?= $i ?><input type="text" name="dato<?= $i ?>" value="<?php echo isset( $actividades[ $i ] )? $actividades[ $i ]: ''; ?>" <?php echo $i == 1 ? 'required': ''; ?> >
        Horas: <input type="number" name="horas<?= $i ?>" value="<?php echo isset( $horas_totales_actividad[ $i ] )? $horas_totales_actividad[ $i ]: 0; ?>" <?php echo $i == 1 ? 'required': ''; ?> >
        <br>
        
        <?php
            }
        ?>
    </p>
    
    <p>
        Horas diarias.<input type="number" name="horas-diarias" value="<?php echo isset( $horas_por_dia[ 1 ] )? $horas_por_dia[ 1 ]: 6.5; ?>" step="0.5">
        <br>
        <input type="checkbox" id="cbox0" name="cbox0" value="0" <?php echo $dia_laboral[ 0 ] == 1 ? 'checked': ''; ?> > <label for="cbox0">D</label>
        <input type="checkbox" id="cbox1" name="cbox1" value="1" <?php echo $dia_laboral[ 1 ] == 1 ? 'checked': ''; ?> > <label for="cbox1">L</label>
        <input type="checkbox" id="cbox2" name="cbox2" value="2" <?php echo $dia_laboral[ 2 ] == 1 ? 'checked': ''; ?> > <label for="cbox2">M</label>
        <input type="checkbox" id="cbox3" name="cbox3" value="3" <?php echo $dia_laboral[ 3 ] == 1 ? 'checked': ''; ?> > <label for="cbox3">M</label>
        <input type="checkbox" id="cbox4" name="cbox4" value="4" <?php echo $dia_laboral[ 4 ] == 1 ? 'checked': ''; ?> > <label for="cbox4">J</label>
        <input type="checkbox" id="cbox5" name="cbox5" value="5" <?php echo $dia_laboral[ 5 ] == 1 ? 'checked': ''; ?> > <label for="cbox5">V</label>
        <input type="checkbox" id="cbox6" name="cbox6" value="6" <?php echo $dia_laboral[ 6 ] == 1 ? 'checked': ''; ?> > <label for="cbox6">S</label>
    </p>
    <p>
        <input type="hidden" name="calcular" value="1">
        <input type="submit" name="Calcular">
    </p>
    
</form>




<?php

    //Funciones

    /**
     * Función que compara una fecha para ver si está dentro de una cadena llena de fechas.
     * @param       texto           Cadena llena de fechas supuestamente de festivos.     
     * @param       texto           Año a evaluar.
     * @param       texto           Mes a evaluar.
     * @param       texto           Día a evaluar.
     * @return      boleano         Retorna si la fecha está o no en esa lista de festivos.
     */
    function comparar_fechas( $cadena_festivos, $aaaa, $mm, $dd )
    {
        $salida = false;

        if( strpos( " ".$cadena_festivos." ", " ".$aaaa."-".$mm."-".$dd." " ) !== false || strpos( " ".$cadena_festivos." ", " ".$aaaa."-".( $mm < 10 ? "0".$mm: $mm )."-".( $dd < 10 ? "0".$dd: $dd )." " ) !== false )
        {
            $salida = true;
        }

        if( strpos( " ".$cadena_festivos." ", " ".$dd."/".$mm."/".$aaaa." " ) !== false || strpos( " ".$cadena_festivos." ", " ".( $dd < 10 ? "0".$dd: $dd )."/".( $mm < 10 ? "0".$mm: $mm )."/".$aaaa." " ) !== false )
        {
            $salida = true;
        }

        return $salida;
    }

