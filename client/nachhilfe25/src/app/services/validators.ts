import { AbstractControl, ValidationErrors } from '@angular/forms';

export class LessonValidators {


  static startBeforeEndValidator(group: AbstractControl): ValidationErrors | null {
    const start = group.get('start')?.value;
    const end = group.get('end')?.value;

    if (!start || !end) return null; // Noch nicht vollständig ausgefüllt
    return start < end ? null : { startAfterEnd: true }; // gibt evtl Fehlerobjekt zurück
  }
}
