@extends('layouts.app')

@section('scripts')
    <link href='{{asset('fullCalendar/core/main.css')}}' rel='stylesheet'/>
    <link href='{{asset('fullCalendar/daygrid/main.css')}}' rel='stylesheet'/>
    <link href='{{asset('fullCalendar/list/main.css')}}' rel='stylesheet'/>
    <link href='{{asset('fullCalendar/timegrid/main.css')}}' rel='stylesheet'/>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial;
            font-size: 14px;
        }

        #calendar {
            max-width: 1000px;
            margin: 40px auto;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {

                defaultDate: new Date(2020, 0, 1),
                plugins: ['dayGrid', 'interaction', 'timeGrid', 'list'],

                header:
                    {
                        left: 'prev,next today Miboton',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                customButtons: {
                    Miboton: {
                        text: "Boton",
                        click: function () {
                            $('#exampleModal').modal('toggle');
                        }
                    }
                },
                dateClick: function (info) {

                    $('#txtFecha').val(info.dateStr);

                    $('#exampleModal').modal('toggle');

                    // console.log(info);
                    // calendar.addEvent({title: "Evento X", date: info.dateStr});
                },
                eventClick: function (info) {

                    console.log(info.event);
                    console.log(info.event.title);
                    console.log(info.event.start);

                    console.log(info.event.end);
                    console.log(info.event.textColor);
                    console.log(info.event.backgroundColor);

                    console.log(info.event.extendedProps.descripcion);


                    $('#txtId').val(info.event.id);
                    $('#txtTitulo').val(info.event.title);

                    mes = (info.event.start.getMonth() + 1);
                    dia = (info.event.start.getDate());
                    anio = (info.event.start.getFullYear());
                    hora = (info.event.start.getHours() + ":" + info.event.start.getMinutes());

                    mes = (mes < 10) ? "0" + mes : mes;
                    dia = (dia < 10) ? "0" + dia : dia;

                    $('#txtFecha').val(anio + "-" + mes + "-" + dia);
                    $('#txtHora').val(hora);
                    $('#txtColor').val(info.event.backgroundColor);
                    $('#txtDescripcion').val(info.event.extendedProps.descripcion);

                    $('#exampleModal').modal();
                },

                events: "{{url('eventos/show')}}"

            });

            calendar.setOption('locale', 'Es')
            calendar.render();

            $('#btnAgregar').click(function () {
                objEvento = recolectarDatosGUI("POST");

                enviarInformacion('', objEvento);
            });

            $('#btnBorrar').click(function () {
                objEvento = recolectarDatosGUI("DELETE");

                enviarInformacion('/' + $('#txtId').val(), objEvento);
            });

            $('#btnModificar').click(function () {
                objEvento = recolectarDatosGUI("PATCH");

                enviarInformacion('/' + $('#txtId').val(), objEvento);
            });

            function recolectarDatosGUI(method) {

                nuevoEvento =
                    {
                        id: $('#txtId').val(),
                        title: $('#txtTitulo').val(),
                        descripcion: $('#txtDescripcion').val(),
                        color: $('#txtColor').val(),
                        TextColor: '#FFFFFFF',
                        start: $('#txtFecha').val() + " " + $('#txtHora').val(),
                        end: $('#txtFecha').val() + " " + $('#txtHora').val(),
                        '_token': $("meta[name='csrf-token']").attr("content"),
                        '_method': method
                    }

                return (nuevoEvento);
            }

            function enviarInformacion(accion, objEvento) {

                $.ajax({
                    type: "POST",
                    url: "{{url('eventos')}}" + accion,
                    data: objEvento,
                    success: function (msg) {
                        console.log(msg);
                        $('#exampleModal').modal('toggle');
                        calendar.refetchEvents();
                    },
                    error: function () {
                        alert("Hay un error");
                    }

                })
            }

        });
    </script>

    <script src='{{asset('fullCalendar/core/main.js')}}'></script>

    <script src='{{asset('fullCalendar/interaction/main.js')}}'></script>

    <script src='{{asset('fullCalendar/daygrid/main.js')}}'></script>
    <script src='{{asset('fullCalendar/list/main.js')}}'></script>
    <script src='{{asset('fullCalendar/timegrid/main.js')}}'></script>

@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos del Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>id</label>
                                        <input type="text" class="form-control" name="txtId" id="txtId">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="text" class="form-control" name="txtFecha" id="txtFecha" disabled>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Titulo</label>
                                        <input type="text" class="form-control" name="txtTitulo" id="txtTitulo">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Hora</label>
                                        <input type="text" class="form-control" name="txtHora" id="txtHora">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Descripcion</label>
                                        <textarea name="txtDescripcion" class="form-control" id="txtDescripcion"
                                                  cols="30"
                                                  rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Color</label>
                                        <input type="color" class="form-control" name="txtColor" id="txtColor">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <button id="btnAgregar" class="btn btn-success">Agregar</button>
                    <button id="btnModificar" class="btn btn-warning">Modificar</button>
                    <button id="btnBorrar" class="btn btn-danger">Borrar</button>
                    <button id="btnCancelar" class="btn btn-default">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="calendar"></div>

@endsection
