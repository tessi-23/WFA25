export enum Gender {
  male = 'male',
  female = 'female',
  other = 'other'
}

export enum Role {
  student = 'student',
  tutor = 'tutor',
  admin = 'admin'
}


export class User {
  constructor(
    public id:number,
    public firstname:string,
    public lastname:string,
    public phone:string,
    public age:number,
    public gender:Gender,
    public qualification:string,
    public role:Role,
    public email:string,
    public password:string,
    public description?:string,
  ) {
  }
}
