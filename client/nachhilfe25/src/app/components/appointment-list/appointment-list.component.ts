import {Component, EventEmitter, Output, Input, output} from '@angular/core';
import {DataView} from 'primeng/dataview';
import {NgClass, NgStyle} from '@angular/common';
import {Button} from 'primeng/button';
import {Dialog} from 'primeng/dialog';
import {User} from '../../classes/user';
import {Toast} from 'primeng/toast';

@Component({
  selector: 'bs-appointment-list',
  imports: [
    DataView,
    NgStyle,
    NgClass,
    Button,
    Dialog,
    Toast
  ],
  templateUrl: './appointment-list.component.html',
})

export class AppointmentListComponent {
  @Input() appointments: any[] = [];
  @Input() type: 'request' | 'upcoming' | 'finished' = 'request';
  @Input() role: string | null = null;

  @Output() accept = new EventEmitter<number>();
  @Output() reject = new EventEmitter<number>();

  selectedTutor: User | undefined;
  selectedStudent: User | undefined;
  visibleTutorModal = false;
  visibleStudentModal = false;

  showTutorDialog(tutor: User) {
    this.selectedTutor = tutor;
    this.visibleTutorModal = true;
  }

  showStudentDialog(student: User) {
    this.selectedStudent = student;
    this.visibleStudentModal = true;
  }
}

