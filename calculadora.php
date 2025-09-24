<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora Analógica</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .calculator-container {
            background-color: #2c3e50;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 320px;
        }

        .calculator-header {
            background-color: #34495e;
            padding: 15px;
            text-align: center;
            color: #ecf0f1;
            font-size: 1.2rem;
            border-bottom: 1px solid #4a6572;
        }

        .display {
            background-color: #1a252f;
            color: #ecf0f1;
            padding: 20px;
            text-align: right;
            font-size: 2.5rem;
            min-height: 100px;
            word-wrap: break-word;
            border-bottom: 2px solid #4a6572;
        }

        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background-color: #4a6572;
        }

        .button {
            background-color: #34495e;
            border: none;
            color: #ecf0f1;
            font-size: 1.5rem;
            padding: 20px 0;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none;
        }

        .button:hover {
            background-color: #4a6572;
        }

        .button:active {
            background-color: #5d6d7e;
            transform: scale(0.95);
        }

        .operator {
            background-color: #3498db;
        }

        .operator:hover {
            background-color: #2980b9;
        }

        .equals {
            background-color: #e74c3c;
            grid-column: span 2;
        }

        .equals:hover {
            background-color: #c0392b;
        }

        .clear {
            background-color: #e67e22;
        }

        .clear:hover {
            background-color: #d35400;
        }

        .zero {
            grid-column: span 2;
        }

        .history {
            background-color: #34495e;
            color: #bdc3c7;
            padding: 10px 20px;
            font-size: 0.9rem;
            min-height: 40px;
            border-top: 1px solid #4a6572;
        }
    </style>
</head>
<body>
    <div class="calculator-container">
        <div class="calculator-header">
            Calculadora Analógica
        </div>
        <form method="post" action="">
            <div class="display" id="display">
                <?php
                // Inicializar variables
                $display = "0";
                $result = "";
                $history = "";

                // Procesar la entrada cuando se presiona un botón
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['input'])) {
                        $input = $_POST['input'];
                        
                        // Si hay un resultado previo, empezar nueva operación
                        if (isset($_POST['result']) && $_POST['result'] !== "") {
                            $display = $input;
                            $result = "";
                        } else {
                            $display = (isset($_POST['display']) && $_POST['display'] !== "0") ? $_POST['display'] . $input : $input;
                        }
                    }
                    
                    // Procesar operadores
                    if (isset($_POST['operator'])) {
                        $operator = $_POST['operator'];
                        $display = (isset($_POST['display']) ? $_POST['display'] : "0") . " " . $operator . " ";
                        $result = "";
                    }
                    
                    // Calcular resultado
                    if (isset($_POST['calculate'])) {
                        $expression = isset($_POST['display']) ? $_POST['display'] : "0";
                        
                        // Reemplazar símbolos para evaluación
                        $expression = str_replace("×", "*", $expression);
                        $expression = str_replace("÷", "/", $expression);
                        
                        // Validar y calcular
                        if (preg_match('/^[0-9+\-*\/\.\s]+$/', $expression)) {
                            // Evaluar la expresión matemática
                            try {
                                // Usar eval con precaución (solo después de validar)
                                eval("\$result = ($expression);");
                                $history = $expression . " =";
                                $display = $result;
                            } catch (Exception $e) {
                                $display = "Error";
                                $result = "";
                            }
                        } else {
                            $display = "Error";
                            $result = "";
                        }
                    }
                    
                    // Limpiar pantalla
                    if (isset($_POST['clear'])) {
                        $display = "0";
                        $result = "";
                        $history = "";
                    }
                    
                    // Punto decimal
                    if (isset($_POST['decimal'])) {
                        $current = isset($_POST['display']) ? $_POST['display'] : "0";
                        // Solo agregar punto si el último número no tiene uno
                        if (!preg_match('/\.\d*$/', $current)) {
                            $display = $current . ".";
                        } else {
                            $display = $current;
                        }
                    }
                }
                ?>
                <input type="hidden" name="display" value="<?php echo htmlspecialchars($display); ?>">
                <input type="hidden" name="result" value="<?php echo htmlspecialchars($result); ?>">
                <?php echo $display; ?>
            </div>
            
            <div class="history">
                <?php echo $history; ?>
            </div>
            
            <div class="buttons-grid">
                <button type="submit" name="clear" class="button clear">C</button>
                <button type="submit" name="operator" value="÷" class="button operator">÷</button>
                <button type="submit" name="operator" value="×" class="button operator">×</button>
                <button type="submit" name="operator" value="-" class="button operator">-</button>
                
                <button type="submit" name="input" value="7" class="button">7</button>
                <button type="submit" name="input" value="8" class="button">8</button>
                <button type="submit" name="input" value="9" class="button">9</button>
                <button type="submit" name="operator" value="+" class="button operator" style="grid-row: span 2;">+</button>
                
                <button type="submit" name="input" value="4" class="button">4</button>
                <button type="submit" name="input" value="5" class="button">5</button>
                <button type="submit" name="input" value="6" class="button">6</button>
                
                <button type="submit" name="input" value="1" class="button">1</button>
                <button type="submit" name="input" value="2" class="button">2</button>
                <button type="submit" name="input" value="3" class="button">3</button>
                <button type="submit" name="calculate" class="button equals" style="grid-row: span 2;">=</button>
                
                <button type="submit" name="input" value="0" class="button zero">0</button>
                <button type="submit" name="decimal" class="button">.</button>
            </div>
        </form>
    </div>

    <script>
        // Agregar interacción con el teclado
        document.addEventListener('keydown', function(event) {
            const key = event.key;
            const form = document.querySelector('form');
            
            if (key >= '0' && key <= '9') {
                // Simular clic en el botón numérico correspondiente
                const button = document.querySelector(`button[name="input"][value="${key}"]`);
                if (button) {
                    button.click();
                }
            } else if (key === '+' || key === '-' || key === '*' || key === '/') {
                // Simular clic en el botón de operador
                let operatorValue = key;
                if (key === '*') operatorValue = '×';
                if (key === '/') operatorValue = '÷';
                
                const button = document.querySelector(`button[name="operator"][value="${operatorValue}"]`);
                if (button) {
                    button.click();
                }
            } else if (key === 'Enter' || key === '=') {
                // Simular clic en el botón de igual
                const button = document.querySelector('button[name="calculate"]');
                if (button) {
                    button.click();
                }
            } else if (key === 'Escape' || key.toLowerCase() === 'c') {
                // Simular clic en el botón de clear
                const button = document.querySelector('button[name="clear"]');
                if (button) {
                    button.click();
                }
            } else if (key === '.') {
                // Simular clic en el botón de punto decimal
                const button = document.querySelector('button[name="decimal"]');
                if (button) {
                    button.click();
                }
            }
        });
    </script>
</body>
</html>