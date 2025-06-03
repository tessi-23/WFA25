import {Component, inject, OnInit, signal} from '@angular/core';
import {Booking} from '../../classes/booking';
import {NachhilfeService} from '../../services/nachhilfe.service';
import {AuthService} from '../../services/auth.service';
import {AppointmentListComponent} from '../../components/appointment-list/appointment-list.component';

@Component({
  selector: 'bs-history',
  imports: [AppointmentListComponent],
  templateUrl: './history.component.html'
})
export class HistoryComponent implements OnInit{
  finishedAppointments = signal<Booking[]>([]);
  private nachhilfeService = inject(NachhilfeService);
  protected authService = inject(AuthService);

  ngOnInit() {
    const role = this.authService.getRole();
    if(role === 'tutor') {
      this.nachhilfeService.getHistoryForTutor().subscribe(res => {
        this.finishedAppointments.set(res);
      })
    } else if(role === 'student') {
      this.nachhilfeService.getHistoryForStudent().subscribe(res => {
        this.finishedAppointments.set(res);
      })
    }
  }
}
