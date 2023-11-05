@extends('prints.layout')

@section('content')
    <div>
        <div style="text-align: center; margin-bottom: 30px;">
            <h2>Encaminhamento Médico</h2>
        </div>
        <div style="margin-bottom: 20px;">
            <span style="font-size: 18px; font-weight: bold;">Para a especialidade:</span> 
            {{ $forwarding->specialty }}
        </div>
        <div style="margin-bottom: 20px;">
            <span style="font-size: 18px; font-weight: bold;">Para o estabelecimento:</span>
            {{ $forwarding->entity }}
        </div>
        <div style="margin-bottom: 20px;">
            <span style="font-size: 18px; font-weight: bold;">História Clínica:</span>
            {{ nl2br($forwarding->clinical_history) }}
        </div>
        <div style="margin-bottom: 20px;">
            <span style="font-size: 18px; font-weight: bold;">Exames realizados:</span>
            {{ nl2br($forwarding->exams) }}
        </div>
        <div style="margin-bottom: 20px;">
            <span style="font-size: 18px; font-weight: bold;">Hipóteses Diagnósticas:</span>
            @foreach ($forwarding->diagnoses as $diagnosis)
                <div>{{ $diagnosis->description }}</div>
            @endforeach
        </div>
        <div style="text-align: right; margin-top: 50px">
            {{ config('entity.city') }},<br/>{{ $forwarding->updated_at->format('d/m/Y') }}
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <div>Dr(a) {{ $forwarding->doctor->name }} </div> 
            <div style="margin-top: 5px;">CRM-{{ $forwarding->doctor->councilState->uf }} {{ $forwarding->doctor->council_number }}</div>
        </div>
    <div>
@endsection