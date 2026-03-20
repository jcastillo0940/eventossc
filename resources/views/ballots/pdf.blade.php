<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Papeletas de Evaluación - {{ $event->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; margin: 0; padding: 0; }
        .ballot { border: 2px solid #000; padding: 20px; margin-bottom: 50px; page-break-after: always; position: relative; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header h2 { margin: 5px 0; font-size: 16px; color: #555; }
        .info { margin-bottom: 20px; font-size: 14px; }
        .info b { display: inline-block; width: 120px; }
        .qr-section { position: absolute; top: 100px; right: 20px; text-align: center; }
        .qr-section img { width: 120px; height: 120px; }
        .criteria-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .criteria-table th, .criteria-table td { border: 1px solid #000; padding: 10px; text-align: left; }
        .criteria-table th { background: #eee; }
        .score-box { width: 80px; height: 40px; border: 2px solid #000; float: right; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; font-style: italic; }
        .box { display: inline-block; width: 60px; height: 30px; border: 1px solid #000; vertical-align: middle; }
    </style>
</head>
<body>
    @foreach($ballots as $ballot)
    <div class="ballot">
        <div class="header">
            <h2>PAPELETA DE EVALUACIÓN OFICIAL</h2>
            <h1>{{ $ballot['event_name'] }}</h1>
        </div>

        <div class="info">
            <p><b>CATEGORÍA:</b> {{ $ballot['category_name'] }}</p>
            <p><b>COMPETIDOR:</b> {{ $ballot['participant_name'] }}</p>
            <p><b>JUEZ:</b> {{ $ballot['judge_name'] }}</p>
        </div>

        <div class="qr-section">
            <img src="data:image/svg+xml;base64,{{ $ballot['qr_code'] }}" alt="QR Code">
            <p style="font-size: 8px; margin-top: 5px;">ESCANEO EXCLUSIVO PARA DIGITADOR</p>
        </div>

        <table class="criteria-table">
            <thead>
                <tr>
                    <th>Criterio de Evaluación</th>
                    <th style="width: 150px; text-align: center;">Puntaje (0 - Max)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ballot['criteria'] as $criterion)
                <tr>
                    <td>
                        <strong>{{ $criterion->name }}</strong><br>
                        <small>Puntaje máximo: {{ $criterion->max_score }}</small>
                    </td>
                    <td>
                        <div style="text-align: right; font-size: 10px;">Max: {{ $criterion->max_score }} pts</div>
                        <div style="width: 100%; height: 30px; border-bottom: 1px solid #000; margin-top: 5px;"></div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Firma del Juez: ___________________________</p>
            <p style="margin-top: 20px;">Esta papeleta debe ser entregada al digitador después de la evaluación.</p>
        </div>
    </div>
    @endforeach
</body>
</html>
