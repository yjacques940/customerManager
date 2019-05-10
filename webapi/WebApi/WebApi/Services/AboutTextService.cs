using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class AboutTextService : BaseCrudService<AboutText>
    {
        public AboutTextService(IWebApiContext context) : base(context)
        {
        }

        internal List<AboutTextAndZoneInformation> GetActiveText()
        {
            var aboutZones = Context.AboutZone.Where(c => c.IsActive).ToList();
            List<AboutTextAndZoneInformation> aboutTextAndZones = new List<AboutTextAndZoneInformation>();
            foreach (var aboutZone in aboutZones)
            {
                AboutTextAndZoneInformation aboutTextAndZone = new AboutTextAndZoneInformation();
                aboutTextAndZone.ZoneDescription = aboutZone.Descr;
                aboutTextAndZone.AboutText = Context.AboutText.FirstOrDefault(c => c.IdZone == aboutZone.Id);
                aboutTextAndZones.Add(aboutTextAndZone);
            }
            return aboutTextAndZones;
        }

        internal AboutText UpdateAboutText(AboutText aboutText)
        {
            var aboutTextToUpdate = Context.AboutText.FirstOrDefault(c => c.Id == aboutText.Id);
            aboutTextToUpdate.TitleFr = aboutText.TitleFr;
            aboutTextToUpdate.TitleEn = aboutText.TitleEn;
            aboutTextToUpdate.DescrFr = aboutText.DescrFr;
            aboutTextToUpdate.DescrEn = aboutText.DescrEn;
            Context.SaveChanges();
            return aboutText;
        }

        internal object GetAboutTextByZone(string zone)
        {
            var zoneId = Context.AboutZone.FirstOrDefault(c => c.Code == zone && c.IsActive).Id;
            return Context.AboutText.FirstOrDefault(c => c.IdZone == zoneId && c.IsActive);
        }

        internal object GetAboutTextById(int id)
        {
            return Context.AboutText.FirstOrDefault(c => c.Id == id && c.IsActive);
        }
    }
}
