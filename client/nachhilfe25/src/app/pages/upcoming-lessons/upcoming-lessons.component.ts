import {Component, inject, OnInit, signal} from '@angular/core';
import {Booking} from '../../classes/booking';
import {NachhilfeService} from '../../services/nachhilfe.service';
import {AuthService} from '../../services/auth.service';
import {AppointmentListComponent} from '../../components/appointment-list/appointment-list.component';

@Component({
  selector: 'bs-upcoming-lessons',
  imports: [AppointmentListComponent],
  templateUrl: './upcoming-lessons.component.html'
})
export class UpcomingLessonsComponent implements OnInit{
  upcomingAppointments = signal<Booking[]>([]);
  private nachhilfeService = inject(NachhilfeService);
  protected authService = inject(AuthService);

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
}
