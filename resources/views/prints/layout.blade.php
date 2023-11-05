<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        table, tr, td {
            border: 1px solid #000;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <div style="margin-bottom: 15px;">
        <table style="width: 100%;">
            <tr style="text-align: center;">
                <td><img style="width: 60%; display: inline-block" src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('images/logo.png'))) }}" /></td>
                <td><h3>{{ config('entity.name') }}</h3></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left: 5px;">
                    Paciente: <b>{{ $patient->name }}</b><br/>
                    Sexo: {{ \App\Enums\Gender::getDescription($patient->gender) }}<br/>
                    Idade: {{ now()->diffInYears($patient->birth_date) }} anos
                </td>
            </tr>
        </table>
    </div>
    <div>
        @yield('content')
    </div>
</body>

</html>