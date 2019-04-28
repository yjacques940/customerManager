using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.DTO
{
    public class AppointmentUserInformation
    {
        public int IdTimeSlot { get; set; }
        public int? IdUser { get; set; }
        public int? IdCustomer { get; set; }
        public string Therapist { get; set; }
        public string VisitReason { get; set; }
        public string HasSeenDoctor { get; set; }
        public string DoctorDiagnostic { get; set; }
    }
}
