using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AppointmentsController : BaseController<AppointmentService, Appointment>
    {
        private readonly CustomerPhoneNumberService customerPhoneNumberService;

        public AppointmentsController(WebApiContext context, AppointmentService service,
            CustomerPhoneNumberService customerPhoneNumberService) : base(service)
        {
            this.customerPhoneNumberService = customerPhoneNumberService;
        }

        [HttpGet, Route("GetByDate/{date}")]
        public ActionResult<IEnumerable<Appointment>> GetAppointmentsByDate(string date)
        {
            return Service.GetAppointmentsByDate(date);
        }

        [HttpGet, Route("AppointmentsAndCustomers")]
        public ActionResult<IEnumerable<CustomerAppointmentInformation>> GetAppointmentAndCustomers()
        {
            return Service.GetAppointmentAndCustomers(customerPhoneNumberService);
        }

        [HttpPost, Route("CheckAppointmentIsAvailable/{appointment}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult AddAppointment([FromBody]AppointmentInformation appointment)
        {
            if (appointment == null)
                return BadRequest();
            var newAppointment = Service.CheckAppointmentIsAvailable(appointment);
            if(newAppointment  == null)
                return Conflict();

            newAppointment.IsActive = true;
            return Ok(Service.AddOrUpdate(newAppointment));
        }
    }
}
