import {Component, OnInit} from '@angular/core';
import {InputText} from 'primeng/inputtext';
import {
  ReactiveFormsModule,
  FormGroup,
  FormBuilder,
  Validators,
  FormArray, AbstractControl,
} from '@angular/forms';
import {Message} from 'primeng/message';
import {Textarea} from 'primeng/textarea';
import {AuthService} from '../../services/auth.service';
import {ActivatedRoute, Router, RouterLink} from '@angular/router';
import {MessageService} from 'primeng/api';
import {Lesson} from '../../classes/lesson';
import {Appointment} from '../../classes/appointment';
import {DatePicker} from 'primeng/datepicker';
import {InputGroup} from 'primeng/inputgroup';
import {InputGroupAddon} from 'primeng/inputgroupaddon';
import {InputNumber} from 'primeng/inputnumber';
import {Button} from 'primeng/button';
import {NachhilfeService} from '../../services/nachhilfe.service';
import {Toast} from 'primeng/toast';
import {LessonFormErrorMessages} from './lesson-form-error-messages';
import {LessonValidators} from '../../services/validators';

@Component({
  selector: 'bs-lesson-form',
  imports: [
    InputText,
    ReactiveFormsModule,
    Message,
    Textarea,
    DatePicker,
    InputGroup,
    InputGroupAddon,
    InputNumber,
    Button,
    RouterLink,
    Toast,
  ],
  templateUrl: './lesson-form.component.html',
  providers: [MessageService]
})
export class LessonFormComponent implements OnInit{
  lessonForm: FormGroup;
  appointments: FormArray;
  lesson: Lesson = Lesson.empty(); // leere Lesson erstellen
  isUpdatingLesson: boolean = false;
  minDate: Date | undefined;
  errors: {[key: string]: string} = {};
  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private authService: AuthService,
    private nachhilfeService: NachhilfeService,
    private router: Router,
    private messageService: MessageService,
  ) {
    this.lessonForm = this.fb.group({});
    this.appointments = this.fb.array([]);
  }

  ngOnInit(): void {
    // aktuelle lesson abrufen
    const lessonId = this.route.snapshot.paramMap.get('lessonId');
    let today = new Date();
    let month = today.getMonth(); // 0-11
    let year = today.getFullYear(); // zB 2025

    // heute als Mindestdatum speichern
    this.minDate = new Date();
    this.minDate.setMonth(month);
    this.minDate.setFullYear(year);

    if(lessonId) { // update
      this.isUpdatingLesson = true;
      this.nachhilfeService.getLessonByID(lessonId).subscribe(lesson => {
        this.lesson = lesson; // signal auf aktuelle lesson setzen
        this.initLessonForm();
      });
    } else { // create
      this.initLessonForm();
    }
  }

  initLessonForm() {
    this.generateAppointmentArray();

    this.lessonForm = this.fb.group({
      id: this.lesson?.id,
      title: this.fb.control(this.lesson?.title, {
        validators: [Validators.required]
      }),
      description: this.fb.control(this.lesson?.description, {
        validators: [Validators.required]
      }),
      appointments: this.appointments
    });
    // Fehlermeldungen updaten, wenn sich was ändert im Formular
    this.lessonForm.statusChanges.subscribe(() => {
      this.updateErrorMessages();
    });
  }

  private generateAppointmentArray() {
    this.appointments = this.fb.array([]);

    if(this.lesson?.appointments) { // update
      for(let appointment of this.lesson.appointments) {
        // strings in Date Objekte umwandeln, damit p-datepicker sie auslesen kann
        const dateObj = new Date(appointment.date);
        const startObj = this.timeStringToDate(appointment.start);
        const endObj = this.timeStringToDate(appointment.end);

        this.appointments.push(this.fb.group({
          id: appointment.id,
          title: [appointment.title, Validators.required],
          date: [dateObj, Validators.required],
          start: [startObj, Validators.required],
          end: [endObj, Validators.required],
          status: appointment.status,
          price: [appointment.price, Validators.required]
        }, { validators: LessonValidators.startBeforeEndValidator }))
      }
    }

    if(this.lesson.appointments?.length === 0) { // create
      this.addAppointmentControl(); // leeres hinzufügen (ohne id)
    }
  }

  // Datum String in Date Objekt umwandeln für p-datepicker
  private timeStringToDate(time: any): Date {
    // Zeit String zerlegen: 13:20:00 in ['13','20','00']
    // mit map(Number) strings in Zahlen umwandeln
    const [hours, minutes, seconds] = time.split(':').map(Number);
    const date = new Date(); // neues Objekt mit aktuellem Datum
    date.setHours(hours, minutes, seconds || 0, 0); // werte setzen
    return date;
  }

  // leeres appointment dazufügen
  protected addAppointmentControl() {
    this.appointments.push(this.fb.group({
      title: ['', Validators.required],
      date: ['', Validators.required],
      start: ['', Validators.required],
      end: ['', Validators.required],
      status: 'available',
      price: [0, Validators.required]
    }, { validators: LessonValidators.startBeforeEndValidator }))
  }


  protected submit() {
    const categoryId = this.route.snapshot.paramMap.get('categoryId'); // aktuelle Kategorie
    const formValue = this.lessonForm.value; // daten der gesamten Kategorie
    const { id, ...formWithoutId } = formValue; // ohne id speichern, da sie autoincrement ist


    if(this.isUpdatingLesson) {
      const lesson = { // kopie erstellen und neue Werte speichern
        ...formValue,
        tutor_id: this.authService.getCurrentUserId(),
        appointments: formValue.appointments.map((app: Appointment) => ({
          id: app.id,
          title: app.title,
          date: this.formatDate(app.date), // in string umwandeln für DB
          start: this.formatTime(app.start),
          end: this.formatTime(app.end),
          status: app.status,
          price: app.price
        }))
      };
      this.nachhilfeService.updateLesson(lesson).subscribe(() => {
        this.messageService.add({
          severity: 'success',
          summary: 'Lesson updated',
          detail: 'Lesson successfully updated'
        });
        this.router.navigate([`/categories/lessons/${categoryId}`]);
      });
    } else {
      const lesson = { // create
        ...formWithoutId, // ohne id erstellen, da autoincrement
        category_id: categoryId,
        tutor_id: this.authService.getCurrentUserId(),
        appointments: formValue.appointments.map((app: Appointment) => ({
          title: app.title,
          date: this.formatDate(app.date), // in string umwandeln für DB
          start: this.formatTime(app.start),
          end: this.formatTime(app.end),
          status: app.status,
          price: app.price
        }))
      };
      this.nachhilfeService.createLesson(lesson).subscribe(() => {
        this.messageService.add({
          severity: 'success',
          summary: 'Lesson created',
          detail: 'Lesson successfully created'
        });
        this.lessonForm.reset();
        this.router.navigate([`/categories/lessons/${categoryId}`]);
      })
    }
  }

  // Date Objekt in string umwandeln
  private formatDate(date: Date): string {
    // ISOString: "2025-06-04T14:25:30.000Z" -> ersten Teil returnen
    return date.toISOString().split('T')[0]; // YYYY-MM-DD
  }

  // Date Objekt in string umwandeln
  private formatTime(time: Date): string {
    // Stunden holen 0-23, in string umwandeln, vorne 0 hinzufügen wenn stunde unter 10
    const hours = time.getHours().toString().padStart(2, '0');
    const minutes = time.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}:00`; // HH:mm:ss Format für DB
  }

  protected clearAppointment(index: number) {
    // bestimmtes appointment leeren
    const appGroup = this.appointments.at(index) as FormGroup;
    appGroup.reset();
  }

  protected removeAppointment(index: number) {
    // bestimmtes appointment entfernen
    this.appointments.removeAt(index);
    this.messageService.add({
      severity: 'info',
      summary: 'Appointment removed',
      detail: 'Appointment marked for removal. Save to apply changes.'
    });
  }

  protected updateErrorMessages() {
    this.errors = {}; // clearen
    for(const message of LessonFormErrorMessages) {
      const control = this.lessonForm.get(message.forControl);
      // wenn es ein control gibt
      // der user schon drin war
      // das Formularfeld gerade invalide ist
      // wenn es das Feld gibt
      // wenn es für den Validator eine Fehlermeldung gibt
      // wenn man die Meldung noch nicht angeschaut hat
      if(control && control.dirty && control.invalid && control.errors &&
        control.errors[message.forValidator] &&
        !control.errors[message.forControl]) {
        this.errors[message.forControl] = message.text; // Text einfügen
      }
    }
  }

  // für FormArray, nur geänderte Controls neu rendern
  protected trackByFn(index: number, item: AbstractControl) {
    return item.get('id')?.value || index;
  }
}


