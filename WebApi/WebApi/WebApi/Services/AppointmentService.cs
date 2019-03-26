using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class AppointmentService : BaseCrudService<Appointment>
    {
        public AppointmentService(IWebApiContext context) : base(context)
        {
        }

        public ActionResult<IEnumerable<Appointment>> GetAppointmentsByDate(string date)
        {
            return Context.Appointments.Where(c => c.AppointmentDateTime.Date == Convert.ToDateTime(date).Date).ToList();
        }
    }
}
