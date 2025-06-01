import {Lesson} from './lesson';
import {Time} from '@angular/common';

export enum Status {
  available = 'available',
  booked = 'booked'
}
export class Appointment {
  constructor(
    public id:number,
    public title:string,
    public date:Date,
    public start:Date,
    public end:Date,
    public status:Status,
    public price:number,
    //public lesson_id:Lesson
  ) {
  }
}
