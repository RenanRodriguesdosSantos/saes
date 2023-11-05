@extends('prints.layout')

@section('content')
    <div>
        <div style="text-align: center; margin-bottom: 30px;">
            <h2>Receita Médica</h2>
        </div>
        <div>
            {!! nl2br($recipe->description) !!}
        </div>
        <div style="text-align: right; margin-top: 50px">
            {{ config('entity.city') }},<br/>{{ $recipe->updated_at->format('d/m/Y') }}
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <div>Dr(a) {{ $recipe->doctor->name }} </div> 
            <div style="margin-top: 5px;">CRM-{{ $recipe->doctor->councilState->uf }} {{ $recipe->doctor->council_number }}</div>
        </div>
    <div>
@endsection