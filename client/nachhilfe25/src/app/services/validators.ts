
import {FormControl} from '@angular/forms';
import {map, Observable} from 'rxjs';
import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';

export class LessonValidators {


  static startBeforeEndValidator(group: AbstractControl): ValidationErrors | null {
    const start = group.get('start')?.value;
    const end = group.get('end')?.value;

    if (!start || !end) return null; // Noch nicht vollständig ausgefüllt

    return start < end ? null : { startAfterEnd: true };
  }
}
