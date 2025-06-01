import {Appointment} from './appointment';
import {User} from './user';

export enum Status {
  pending = 'pending',
  accepted = 'accepted',
  rejected = 'rejected',
  finished = 'finished'
}

export class Booking {
  constructor(
    public id:number,
    public status:Status,
    public appointment:Appointment,
    public tutor:User,
    //public student_id:User,
    public comment?:string,
  ) {
  }
}
