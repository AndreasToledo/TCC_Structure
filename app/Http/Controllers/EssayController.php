<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EssayController extends Controller
{    
    public function resultado(Request $request){
        $texto = $request->input('texto');

        $process = proc_open(
            'python3 /caminho/completo/para/inferencia.py',
            [
                0 => ['pipe', 'r'],  // STDIN
                1 => ['pipe', 'w'],  // STDOUT
                2 => ['pipe', 'w']   // STDERR
            ],
            $pipes
        );

        if (is_resource($process)) {
            fwrite($pipes[0], $texto);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $returnCode = proc_close($process);

            // Log para depuração binária (opcional)
            error_log("Saída bruta: " . bin2hex($output));

            if ($returnCode === 0) {
                \Log::debug('Saída do Python: ' . $output);

                // Força para UTF-8 limpo (até mesmo se estiver bugado)
                $nota = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                $nota = trim($nota);

                if (!mb_check_encoding($nota, 'UTF-8')) {
                    return response()->json(['erro' => 'Saída inválida: encoding malformado'], 500);
                }

                // Resposta como texto formatado, se preferir JSON é só mudar
                return response("<pre>Output cru do Python:\n" . htmlspecialchars($nota) . "</pre>", 200)
                       ->header('Content-Type', 'text/html; charset=utf-8');
            } else {
                $error = mb_convert_encoding($error, 'UTF-8', 'UTF-8');
                return response()->json(['erro' => 'Erro ao executar o script: ' . $error], 500);
            }
        } else {
            return response()->json(['erro' => 'Falha ao iniciar o processo'], 500);
        }
    }
}