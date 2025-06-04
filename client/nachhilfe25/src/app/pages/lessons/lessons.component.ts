import {Component, inject, OnInit, signal} from '@angular/core';
import {Lesson} from '../../classes/lesson';
import {NachhilfeService} from '../../services/nachhilfe.service';
import {ActivatedRoute, Params, RouterLink} from '@angular/router';
import {Accordion, AccordionContent, AccordionHeader, AccordionPanel} from 'primeng/accordion';
import {Button} from 'primeng/button';
import {Dialog} from 'primeng/dialog';
import {AuthService} from '../../services/auth.service';
import {ConfirmationService, MessageService} from 'primeng/api';
import {Toast} from 'primeng/toast';
import {ConfirmDialog} from 'primeng/confirmdialog';
import {FormControl, FormGroup, FormsModule, ReactiveFormsModule} from '@angular/forms';
import {InputText} from 'primeng/inputtext';

@Component({
  selector: 'bs-lessons',
  imports: [
    Accordion,
    AccordionPanel,
    AccordionHeader,
    AccordionContent,
    Button,
    Dialog,
    Toast,
    ConfirmDialog,
    FormsModule,
    InputText,
    ReactiveFormsModule,
    RouterLink,
  ],
  templateUrl: './lessons.component.html',
  providers: [
    ConfirmationService, MessageService
  ]
})
export class LessonsComponent implements OnInit{
  lessons = signal<Lesson[]>([]);
  requestForm: FormGroup;
  visibleInfoModal: boolean = false;
  visibleTutorModal: boolean = false;
  categoryId :number = 0;

  protected nachhilfeService = inject(NachhilfeService);
  protected authService = inject(AuthService);
  private route = inject(ActivatedRoute);
  private confirmationService = inject(ConfirmationService);
  private messageService = inject(MessageService);

  constructor() {
    this.requestForm = new FormGroup({});
  }

  ngOnInit() {
    this.requestForm = new FormGroup({
      message: new FormControl<string | null>(null)
    });

    const role = this.authService.getRole();
    const params:Params = this.route.snapshot.params; // categoryId holen
    this.categoryId = params['categoryId'];

    if(role === 'tutor') { // eigene erstellte lessons
      this.nachhilfeService.getLessonsForTutor(this.categoryId).subscribe(
        res => {
          this.lessons.set(res)
        }
      );
    } else if (role === 'student') { // nur die Termine, die student noch nicht gebucht hat
      this.nachhilfeService.getLessonsForStudent(this.categoryId).subscribe(
        res => {
          this.lessons.set(res);
        }
      )
    } else {
      // alle lessons holen
      this.nachhilfeService.getLessonsByID(this.categoryId).subscribe(
        res => {
          this.lessons.set(res)
        }
      );
    }
  }

  showTutorDialog() {
    this.visibleTutorModal = true;
  }

  showInfoDialog() {
    this.visibleInfoModal = true;
  }

  confirmDeleteAppointment(lessonId: number, appointmentId: number, event: Event) {
    this.confirmationService.confirm({
      target: event.target as EventTarget,
      key: 'deleteAppointment',
      message: 'Do you want to delete this appointment?',
      header: 'Danger Zone',
      icon: 'pi pi-info-circle',
      rejectLabel: 'Cancel',
      rejectButtonProps: {
        label: 'Cancel',
        severity: 'secondary',
        outlined: true,
      },
      acceptButtonProps: {
        label: 'Delete',
        severity: 'danger',
      },

      accept: () => {
        this.nachhilfeService.deleteAppointment(appointmentId).subscribe({
          next: () => {
            // lessons aktualisieren (map erstellt neue Liste)
            const updatedLessons = this.lessons()
              .map(lesson => { // jede lesson durchlaufen
                if (lesson.id === lessonId) { // lesson mit gelöschtem appointment gefunden
                  return {
                    ...lesson, // alle eigenschaften der ursprünglichen lesson kopieren
                    // filter() entfernt gelöschtes appointment
                    appointments: lesson.appointments.filter(app => app.id !== appointmentId)
                  };
                }
                return lesson; // sonst unverändert zurückgeben
              })
              // filter() entfernt lessons ohne available appointments
              .filter(lesson => lesson.appointments.some(app => app.status === 'available'));

            this.lessons.set(updatedLessons); // signal neu setzen
            this.messageService.add({ severity: 'info', summary: 'Confirmed', detail: 'Appointment deleted' });
          }
        });
      },
      reject: () => {
        this.messageService.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected' });
      },
    })
  }


  confirmSendBookingRequest(lesson: Lesson, appointmentId: number, event: Event) {
    this.confirmationService.confirm({
      target: event.target as EventTarget,
      key: 'booking',
      header: 'Send Booking Request',
      message: 'Type in your message for other date or time requests.',
      icon: 'pi pi-exclamation-circle',
      rejectButtonProps: {
        label: 'Cancel',
        icon: 'pi pi-times',
        outlined: true,
        size: 'small'
      },
      acceptButtonProps: {
        label: 'Send',
        icon: 'pi pi-send',
        size: 'small'
      },

      accept: () => {
        // Wenn message leer dann leer lassen
        const userMessage = this.requestForm.get('message')?.value ?? null;

        this.nachhilfeService.sendBookingRequest(appointmentId, lesson.tutor.id, userMessage).subscribe({
          next: () => {
            // lessons aktualisieren (map erstellt neue Liste)
            const updatedLessons = this.lessons()
              .map(l => { // jede lesson durchlaufen
                if (l.id === lesson.id) { // lesson mit request-appointment gefunden
                  return {
                    ...l, // alle eigenschaften der ursprünglichen lesson kopieren
                    // filter() entfernt request-appointment
                    appointments: l.appointments.filter(app => app.id !== appointmentId)
                  };
                }
                return l; // sonst unverändert zurückgeben
              })
              // filter() entfernt lessons ohne available appointments
              .filter(lesson => lesson.appointments.some(app => app.status === 'available'));

            this.lessons.set(updatedLessons); // signal neu setzen

            this.messageService.add({ severity: 'info', summary: 'Confirmed', detail: 'Request sent'});
            this.requestForm.reset();
          },
          error: (err) => {
            console.log(err);
            this.requestForm.reset();
            this.messageService.add({ severity: 'error', summary: 'Error', detail: 'Request could not be sent'});
          }
        });
      },
      reject: () => {
        this.messageService.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected' });
      },
    })
  }
}
