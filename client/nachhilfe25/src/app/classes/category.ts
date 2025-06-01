import {Lesson} from './lesson';

export enum CategoryTitleType {
  KWM = 'KWM',
  SE = 'SE',
  MTD = 'MTD',
  HSD = 'HSD',
  DDP = 'DDP',
  DA = 'DA',
  MBI = 'MBI',
  MC = 'MC',
  SI = 'SI'
}

export class Category {
  constructor(
    public id:number,
    public title:CategoryTitleType,
    public description:string,
    public lessons:Lesson[]
  ) {}
}
