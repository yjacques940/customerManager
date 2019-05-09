using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class AboutTextService : BaseCrudService<AboutText>
    {
        public AboutTextService(IWebApiContext context) : base(context)
        {
        }

        internal List<AboutText> GetActiveText()
        {
            return Context.AboutText.Where(c => c.IsActive).ToList();
        }

        internal AboutText AddNewText(AboutText aboutText)
        {
            List<AboutText> activeAboutTexts = Context.AboutText.Where(c => c.IsActive).ToList();
            foreach (var activeAboutText in activeAboutTexts)
            {
                activeAboutText.IsActive = false;
            }
            aboutText.IsActive = true;
            Context.Add(aboutText);
            Context.SaveChanges();
            return aboutText;
        }

        internal object GetAboutTextById(int id)
        {
            return Context.AboutText.FirstOrDefault(c => c.Id == id && c.IsActive);
        }
    }
}
