import {Component, inject, OnInit, signal} from '@angular/core';
import {Booking} from '../../classes/booking';
import {NachhilfeService} from '../../services/nachhilfe.service';
import {AuthService} from '../../services/auth.service';
import {User} from '../../classes/user';
import {AppointmentListComponent} from '../../components/appointment-list/appointment-list.component';

@Component({
  selector: 'bs-history',
  imports: [AppointmentListComponent],
  templateUrl: './history.component.html',
  styles: ``
})
export class HistoryComponent implements OnInit{
  finishedAppointments = signal<Booking[]>([]);
  private nachhilfeService = inject(NachhilfeService);
  protected authService = inject(AuthService);

  visibleTutorModal: boolean = false;
  visibleStudentModal: boolean = false;
  selectedTutor: any = null;
  selectedStudent: any = null;

  ngOnInit() {
    const role = this.authService.getRole();
    if(role === 'tutor') {
      this.nachhilfeService.getHistoryForTutor().subscribe(res => {
        console.log(res);
        this.finishedAppointments.set(res);
      })
    } else if(role === 'student') {
      this.nachhilfeService.getHistoryForStudent().subscribe(res => {
        this.finishedAppointments.set(res);
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
