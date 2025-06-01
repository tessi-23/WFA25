import {Component, inject, OnInit, signal} from '@angular/core';
import {Button} from "primeng/button";
import {DataView} from "primeng/dataview";
import {Dialog} from "primeng/dialog";
import {NgClass, NgForOf, NgIf} from "@angular/common";
import {Toast} from "primeng/toast";
import {Booking} from '../../classes/booking';
import {NachhilfeService} from '../../services/nachhilfe.service';
import {AuthService} from '../../services/auth.service';
import {User} from '../../classes/user';
import {AppointmentListComponent} from '../../components/appointment-list/appointment-list.component';

@Component({
  selector: 'bs-upcoming-lessons',
  imports: [
    Button,
    DataView,
    Dialog,
    NgForOf,
    NgIf,
    Toast,
    NgClass,
    AppointmentListComponent
  ],
  templateUrl: './upcoming-lessons.component.html',
  styles: ``
})
export class UpcomingLessonsComponent implements OnInit{
  upcomingAppointments = signal<Booking[]>([]);
  private nachhilfeService = inject(NachhilfeService);
  protected authService = inject(AuthService);

  visibleTutorModal: boolean = false;
  visibleStudentModal: boolean = false;
  selectedTutor: any = null;
  selectedStudent: any = null;

  ngOnInit() {
    const role = this.authService.getRole();
    if(role === 'tutor') {
      this.nachhilfeService.getBookedLessonsForTutor().subscribe(res => {
        this.upcomingAppointments.set(res);
      })
    } else if(role === 'student') {
      this.nachhilfeService.getBookedLessonsForStudent().subscribe(res => {
        this.upcomingAppointments.set(res);
      })
    }
  }

  showTutorDialog(tutor: User | null) {
    this.selectedTutor = tutor;
    this.visibleTutorModal = true;
  }

  showStudentDialog(student: User | null) {
    this.selectedStudent = student;
    this.visibleStudentModal = true;
  }
}
