<?php
//Acceso Publico
Route::post('login', 'AuthController@login');

//Primer Mildware de login con jwt y auth
Route::group(['middleware' => ['jwt.auth']], function() {

		Route::resource('/carrera', 'CarreraController')->except(['create', 'edit']);
    	Route::get('mostrar_carreras_borradas', 'CarreraController@mostrar_borrados');
		Route::get('carrera/restaurar/{id}', 'CarreraController@recuperar_borrado');
    	Route::post('carrera/eliminar_varios', 'CarreraController@destroy_all');
        Route::post('alumno/eliminar_varios', 'AlumnoController@destroy_all');
		Route::post('carrera/restaurar_varios', 'CarreraController@recuperar_varios');

		Route::resource('/asignatura', 'AsignaturaController')->except(['create', 'edit']);
		Route::get('mostrar_asignaturas_borradas', 'AsignaturaController@mostrar_borrados');
		Route::get('asignatura/restaurar/{id}', 'AsignaturaController@recuperar_borrado');
		Route::post('asignatura/eliminar_varios', 'AsignaturaController@destroy_all');
		Route::post('asignatura/restaurar_varios', 'AsignaturaController@recuperar_varios');

		Route::resource('/alumno', 'AlumnoController')->except(['create', 'edit']);
		Route::get('mostrar_alumnos_borrados', 'AlumnoController@mostrar_borrados');
		Route::get('alumno/restaurar/{id}', 'AlumnoController@recuperar_borrado');
		Route::post('alumno/eliminar_varios', 'AlumnoController@destroy_all');
    	Route::post('alumno/restaurar_varios', 'AlumnoController@recuperar_varios');

		Route::resource('/profesor', 'ProfesorController')->except(['create', 'edit']);
		Route::resource('/usuario', 'UserController')->except(['create', 'edit']);
		Route::get('mostrar_profesores_borrados', 'ProfesorController@mostrar_borrados');
		Route::get('profesor/restaurar/{id}', 'ProfesorController@recuperar_borrado');
    	Route::post('profesor/eliminar_varios', 'ProfesorController@destroy_all');
    	Route::post('profesor/restaurar_varios', 'ProfesorController@recuperar_varios');

		Route::resource('/seccion', 'SeccionController')->except(['create', 'edit']);
		Route::get('mostrar_secciones_borradas', 'SeccionController@mostrar_borrados');
		Route::get('seccion/restaurar/{id}', 'SeccionController@recuperar_borrado');
    	Route::post('seccion/eliminar_varios', 'SeccionController@destroy_all');
    	Route::post('seccion/restaurar_varios', 'SeccionController@recuperar_varios');
    	Route::post('seccion/agregar_alumnos', 'SeccionController@agregar_alumnos');
    	Route::get('seccion/listado_alumnos/{id}', 'SeccionController@listado_alumnos');
    	Route::post('seccion/disponibles', 'SeccionController@alumnos_disponibles');

    	Route::post('nota/agregar_nota', 'NotaController@agregar_nota');
    	Route::post('nota/agregar_varias_notas', 'NotaController@agregar_varias_notas');
    	Route::post('nota/ver_nota', 'NotaController@ver_nota');

    	Route::post('register', 'AuthController@register');

    	Route::get('logout', 'AuthController@logout');
});