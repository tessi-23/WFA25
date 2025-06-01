export class ErrorMessage {
  constructor(
    public forControl: string,
    public forValidator: string,
    public text: string
  ) { }
}
export const LessonFormErrorMessages = [
  new ErrorMessage('title', 'required', 'Please enter a title'),
  new ErrorMessage('description', 'required', 'Please enter a description'),
];
