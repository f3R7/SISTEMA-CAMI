import { HttpHeaders } from "@angular/common/http";

export class Auth { 
 static  autorizar(){
    const tok =  localStorage.getItem('token-cami');
    const httpOptions = {
    headers: new HttpHeaders({
      'Content-Type':  'application/json',
      'Authorization': 'Bearer ' + tok
    })};
    return httpOptions;
  }

  static  autorizarimg(){
    const tok =  localStorage.getItem('token-cami');
    const httpOptions = {
    headers: new HttpHeaders({
      'Authorization': 'Bearer ' + tok
    })};
    return httpOptions;
  }


}