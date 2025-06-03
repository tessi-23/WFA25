import {Component, inject, OnInit, signal} from '@angular/core';
import {DataView} from 'primeng/dataview';
import {NgClass, NgForOf, NgIf, NgStyle} from '@angular/common';
import {NachhilfeService} from '../../services/nachhilfe.service';
import {AuthService} from '../../services/auth.service';
import {Booking} from '../../classes/booking';
import { Status as BookingStatus } from '../../classes/booking';
import {Button} from 'primeng/button';
import {Dialog} from 'primeng/dialog';
import {User} from '../../classes/user';
import {MessageService} from 'primeng/api';
import {Toast} from 'primeng/toast';
import {AppointmentListComponent} from '../../components/appointment-list/appointment-list.component';

@Component({
  selector: 'bs-requests',
  imports: [AppointmentListComponent],
  templateUrl: './requests.component.html',
  providers: [MessageService],
})
export class RequestsComponent implements OnInit{
  requests = signal<Booking[]>([]);
  private nachhilfeService = inject(NachhilfeService);
  protected authService = inject(AuthService);
  private messageService = inject(MessageService);

  ngOnInit() {
    const role = this.authService.getRole();
    if(role === 'student') { // gesendeten Requests
      this.nachhilfeService.getBookingRequestsForStudent().subscribe(res => {
        this.requests.set(res);
      })
    } else if (role === 'tutor') { // empfangene Requests
      this.nachhilfeService.getBookingRequestsForTutor().subscribe(res => {
        this.requests.set(res);
      })
    }
  }

  acceptBookingRequest(bookingId:number) {
    this.nachhilfeService.acceptBooking(bookingId).subscribe({
      next: () => {
        // requests aktualisieren (ohne akzeptiertes booking)
        this.requests.set(this.requests()
          // array durchlaufen und nur die requests liefern, die die bedingung erfüllen
          .filter(req => req.id !== bookingId));
        this.messageService.add({ severity: 'info', summary: 'Confirmed', detail: 'Appointment accepted' });
      }, error: (err) => {
        this.messageService.add({ severity: 'error', summary: 'Failed', detail: 'Appointment could not be accepted' });
      }
    });
  }

  rejectBookingRequest(bookingId:number) {
    this.nachhilfeService.rejectBooking(bookingId).subscribe({
      next: () => {
        const updatedBookings = this.requests() // kopie von request array
          .map(req => {
              if (req.id === bookingId) { // die rejected buchung
                return { ...req, status: 'rejected' as BookingStatus }; // neues Objekt, aber status auf rejected ändern
              }
              return req; // sonst buchung so lassen
            });
        this.requests.set(updatedBookings); // requests updaten

        this.messageService.add({ severity: 'info', summary: 'Confirmed', detail: 'Appointment rejected' });
      }, error: (err) => {
        this.messageService.add({ severity: 'error', summary: 'Failed', detail: 'Appointment could not be rejected' });
      }
    });
  }
}
