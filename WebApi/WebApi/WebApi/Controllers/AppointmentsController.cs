using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using WebApi.Data;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AppointmentsController : BaseController<AppointmentService, Appointment>
    {
        public AppointmentsController(WebApiContext context, AppointmentService service) : base(service)
        {
        }

        [HttpGet, Route("GetByDate/{date}")]
        public ActionResult<IEnumerable<Appointment>> GetCustomer(string date)
        {
            return Service.GetAppointmentsByDate(date);
        }
    }
}
