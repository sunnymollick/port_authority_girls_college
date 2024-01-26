<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{

   public function stdclass()
   {
      return $this->belongsTo(StdClass::class, 'admitted_class');
   }

   public function section()
   {
      return $this->belongsTo(Section::class, 'admitted_section');
   }

   public function readableSubjects()
   {
      return $this->hasMany(AdmissionReadableSubject::class, 'admission_id');
   }

   public function sscSubjects()
   {
      return $this->hasMany(AdmissionSscResult::class, 'admission_id');
   }

}
