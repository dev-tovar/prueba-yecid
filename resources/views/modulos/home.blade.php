@extends('../layout')
@section('components')

@include('../modulos.includes.bar')
@include('../modulos.includes.menu')

<v-main class="pb-0" style="background: #f3f3f3;">
    <router-view></router-view>
</v-main>
@endsection
