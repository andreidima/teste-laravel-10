@extends('layouts.app')

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-5">
            <div class="card culoare2">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    Bine ai venit <b>{{ auth()->user()->name ?? '' }}</b>!
                </div>
            </div>
        </div>
    </div> --}}

    <style>
        .fm {
            box-shadow: 0px 5px 20px #8d8d8d;
        }
        .fm-modal .modal-dialog {
        background-color: white;
        margin: 20px;
        padding: 20px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" id="fm-main-block" style="height: 700px;">
                <div id="fm" style="height: 700px;"></div>
            </div>
        </div>
    </div>


    <!-- File manager -->
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');

        fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
          window.opener.fmSetLink(fileUrl);
          window.close();
        });
      });
    </script>

{{-- </div> --}}
@endsection

