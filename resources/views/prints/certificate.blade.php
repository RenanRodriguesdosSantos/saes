@extends('prints.layout')

@section('content')
    <div>
        <div style="text-align: center; margin-bottom: 30px;">
            <h2>{{ $certificate->type == \App\Enums\CertificateType::NORMAL ? 'Atestado Médico' : 'Atestado de Comparecimento' }}</h2>
        </div>
        @if($certificate->type == \App\Enums\CertificateType::NORMAL)
        <div>
            Atesto para os devidos fins, que {{ $patient->gender == \App\Enums\Gender::MASCULINE ? 'o' : 'a' }} 
            paciente <b>{{ $patient->name }}</b> deverá ficar afastad{{ $patient->gender == \App\Enums\Gender::MASCULINE ? 'o' : 'a' }} 
            das suas atividades {{ \App\Enums\CertificateActivity::getDescription($certificate->activity) }}
            por {{ $certificate->duration }} {{ \App\Enums\DurationType::getDescription($certificate->duration_type) }} apartir de {{ $certificate->start_at->format('d/m/Y') }}.

            @if($certificate->show_cids)
                <div style="margin-top: 15px;">
                    CID(s): 
                    @foreach ($certificate->appointment->diagnoses as $diagnosis)
                        {{ $diagnosis->code }}@if($loop->last).@else, @endif
                    @endforeach
                </div>
            @endif
        </div>
        @else
        <div>
            Atesto para os devidos fins, que {{ $patient->gender == \App\Enums\Gender::MASCULINE ? 'o' : 'a' }} 
            paciente <b>{{ $patient->name }}</b> compareceu a esta unidade no período de <b>{{ $certificate->start_at->format('d/m/Y H:i') }}</b> à <b>{{ $certificate->end_at->format('d/m/Y H:i') }}</b>
            para realização de atendimento médico.
        </div>
        @endif
        <div style="text-align: right; margin-top: 50px">
            {{ config('entity.city') }},<br/>{{ $certificate->updated_at->format('d/m/Y') }}
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <div>Dr(a) {{ $certificate->doctor->name }} </div> 
            <div style="margin-top: 5px;">CRM-{{ $certificate->doctor->councilState->uf }} {{ $certificate->doctor->council_number }}</div>
        </div>
    <div>
@endsection