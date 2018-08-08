<?php

interface AccountInterface
{
    /**
     * Ajout d'une observation
     */
    public function addObservation();

    /**
     * Ajout d'un commentaire sur une observation publiée
     */
    public function addCommentToObservation();

    /**
     * Changement de la photo de profil (avatar)
     */
    public function changeAvatar();

    /**
     * Changement du mot de passe
     */
    public function changePassword();

    /**
     * Changement de la biographie
     */
    public function changeBiography();
}
