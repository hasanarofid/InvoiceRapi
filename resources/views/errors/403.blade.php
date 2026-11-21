@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Tidak Diizinkan')
@section('message', $exception->getMessage() ?: 'Maaf, Anda tidak memiliki izin untuk mengakses halaman atau data ini.')

