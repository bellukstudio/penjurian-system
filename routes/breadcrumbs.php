<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// breadcrumb admin

// data user
Breadcrumbs::for('dataUser', function (BreadcrumbTrail $trail) {
    $trail->push('Data User', route('manageUser.index'));
});
Breadcrumbs::for('dataUser.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('dataUser');
    $trail->push('Edit User', route('manageUser.edit', $user->id));
});
Breadcrumbs::for('dataUser.create', function (BreadcrumbTrail $trail) {
    $trail->parent('dataUser');
    $trail->push('Tambah User', route('manageUser.create'));
});

// data juri
Breadcrumbs::for('dataJuri', function (BreadcrumbTrail $trail) {
    $trail->push('Data Juri', route('manageJury.index'));
});
Breadcrumbs::for('dataJuri.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('dataJuri');
    $trail->push('Edit Juri', route('manageJury.edit', $user->id));
});
Breadcrumbs::for('dataJuri.create', function (BreadcrumbTrail $trail) {
    $trail->parent('dataJuri');
    $trail->push('Tambah Juri', route('manageJury.create'));
});

// data acara
Breadcrumbs::for('dataEvents', function (BreadcrumbTrail $trail) {
    $trail->push('Data Acara', route('manageEvents.index'));
});
Breadcrumbs::for('dataEvents.edit', function (BreadcrumbTrail $trail, $event) {
    $trail->parent('dataEvents');
    $trail->push('Edit Acara', route('manageEvents.edit', $event->id));
});
Breadcrumbs::for('dataEvents.create', function (BreadcrumbTrail $trail) {
    $trail->parent('dataEvents');
    $trail->push('Tambah Acara', route('manageEvents.create'));
});

// data peserta
Breadcrumbs::for('dataParticipants', function (BreadcrumbTrail $trail) {
    $trail->push('Data Peserta', route('manageParticipants.index'));
});

// data lomba
Breadcrumbs::for('dataContest', function (BreadcrumbTrail $trail) {
    $trail->push('Data Lomba', route('manageContests.index'));
});
Breadcrumbs::for('dataContest.edit', function (BreadcrumbTrail $trail, $constest) {
    $trail->parent('dataContest');
    $trail->push('Edit Lomba', route('manageContests.edit', $constest->id));
});
Breadcrumbs::for('dataContest.create', function (BreadcrumbTrail $trail) {
    $trail->parent('dataContest');
    $trail->push('Tambah Lomba', route('manageContests.create'));
});

Breadcrumbs::for('dataContest.show', function (BreadcrumbTrail $trail, $constest) {
    $trail->parent('dataContest');
    $trail->push('Data Peserta Lomba ' . $constest->name . '', route('manageParticipants.indexParticipants', $constest->id));
});

Breadcrumbs::for('dataContest.editParticipant', function (BreadcrumbTrail $trail, $participant) {
    $trail->parent('dataContest');
    $trail->push('Edit Peserta', route('manageParticipants.edit', $participant->id));
});
Breadcrumbs::for('dataContest.createParticipant', function (BreadcrumbTrail $trail, $contest) {
    $trail->parent('dataContest.show', $contest);
    $trail->push('Tambah Peserta ' . $contest->name . '', route('manageParticipants.create', $contest->id));
});
Breadcrumbs::for('dataContest.showJury', function (BreadcrumbTrail $trail, $contest) {
    $trail->parent('dataContest');
    $trail->push('Data Juri ' . $contest->name . '', route('manageJury.showJuryInContest', $contest->id));
});
Breadcrumbs::for('dataContest.createJuryContest', function (BreadcrumbTrail $trail, $contest, $event) {
    $trail->parent('dataContest.showJury', $contest);
    $trail->push('Tambah Juri ' . $contest->name . '', route('manageJury.createJuryInContest', [$contest->id, $event->id]));
});


// breadcrumbs user
//juri
Breadcrumbs::for('dataJuriUser', function (BreadcrumbTrail $trail) {
    $trail->push('Data Juri', route('manageJuri.index'));
});
Breadcrumbs::for('dataJuriUser.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('dataJuriUser');
    $trail->push('Edit Juri ' . $user->name . '', route('manageJuri.edit', $user->id));
});

// acara
Breadcrumbs::for('dataEventsUser', function (BreadcrumbTrail $trail) {
    $trail->push('Data Acara', route('manageEvent.index'));
});
Breadcrumbs::for('dataEventsUser.edit', function (BreadcrumbTrail $trail, $event) {
    $trail->parent('dataEventsUser');
    $trail->push('Edit Acara ' . $event->name . '', route('manageEvent.edit', $event->id));
});
//lomba
Breadcrumbs::for('dataEventsUser.showContest', function (BreadcrumbTrail $trail, $event) {
    $trail->parent('dataEventsUser');
    $trail->push('Data Lomba ' . $event->name . '', route('manageContest.show', $event->id));
});
Breadcrumbs::for('dataEventsUser.createContest', function (BreadcrumbTrail $trail, $event) {
    $trail->parent('dataEventsUser.showContest', $event);
    $trail->push('Tambah Lomba', route('manageContest.saveContest', $event->id));
});
Breadcrumbs::for('dataEventsUser.showContest.edit', function (BreadcrumbTrail $trail, $contest, $event) {
    $trail->parent('dataEventsUser.showContest', $event);
    $trail->push('Edit Lomba ' . $contest->name . '', route('manageContest.edit', $contest->id));
});
// peserta
Breadcrumbs::for('dataEventsUser.showContest.participant', function (BreadcrumbTrail $trail, $contest, $event) {
    $trail->parent('dataEventsUser.showContest', $event);
    $trail->push('Peserta Lomba ' . $contest->name . '', route('manageParticipant.showParticipant', [$contest->id, $event->id]));
});
Breadcrumbs::for('dataEventsUser.showContest.participant.edit', function (BreadcrumbTrail $trail, $contest, $event) {
    $trail->parent('dataEventsUser.showContest.participant', $contest, $event);
    $trail->push('Edit Peserta ', route('manageParticipant.editParticipant', [$contest->id, $event->id]));
});
Breadcrumbs::for('dataEventsUser.showContest.participant.create', function (BreadcrumbTrail $trail, $contest, $event) {
    $trail->parent('dataEventsUser.showContest.participant', $contest, $event);
    $trail->push('Tambah Peserta ', route('manageParticipant.editParticipant', [$contest->id, $event->id]));
});
// juri
Breadcrumbs::for('dataEventsUser.showContest.showJury', function (BreadcrumbTrail $trail, $contest, $event) {
    $trail->parent('dataEventsUser.showContest', $event);
    $trail->push('Juri Lomba ' . $contest->name . '', route('manageJuri.show',$contest->id));
});
Breadcrumbs::for('dataEventsUser.showContest.showJury.create', function (BreadcrumbTrail $trail, $contest, $event) {
    $trail->parent('dataEventsUser.showContest.showJury', $contest,$event);
    $trail->push('Tambah Juri', route('manageJuri.saveJury', [$contest->id, $event->id]));
});
Breadcrumbs::for('dataEventsUser.showContest.showJury.edit', function (BreadcrumbTrail $trail, $contest, $event) {
    $trail->parent('dataEventsUser.showContest.showJury', $contest,$event);
    $trail->push('Edit Juri', route('manageJuri.editJury', $contest->id));
});



/// breadcrumbs juri
Breadcrumbs::for('startAssessment',function(BreadcrumbTrail $trail,$event){
    $trail->push('Data Lomba',route('juryAssessment.startAssessment',$event->token));
});
Breadcrumbs::for('startAssessment.create',function(BreadcrumbTrail $trail,$event,$contest){
    $trail->parent('startAssessment',$event);
    $trail->push('Form Penilaian',route('juryAssessment.createAssessment',$contest->id));
});
Breadcrumbs::for('startAssessment.show',function(BreadcrumbTrail $trail,$event,$contest){
    $trail->parent('startAssessment',$event);
    $trail->push('Data Penilaian',route('juryAssessment.showAssessment',$contest->id));
});