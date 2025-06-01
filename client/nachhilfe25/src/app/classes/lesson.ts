import {Category} from './category';
import {Appointment} from './appointment';
import {Gender, Role, User} from './user';

export class Lesson {
  constructor(
    public id:number,
    public title:string,
    public description:string,
    public tutor_id:User,
    public tutor:User,
    //public category_id:Category,
    public appointments:Appointment[]
  ) {}

  static empty(): Lesson {
    const emptyUser: User = {
      id: 0,
      firstname: '',
      lastname: '',
      phone: '',
      age: 0,
      gender: Gender.other,
      qualification: '',
      role: Role.tutor,
      email: '',
      password: '',
      description: ''
    };

    return new Lesson(0, '', '', emptyUser, emptyUser, []);
  }
}
